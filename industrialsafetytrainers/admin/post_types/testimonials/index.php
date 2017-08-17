<?php

add_action( 'init', 'create_custom_testimonial_post_types' );

//CUSTOM POST TYPE
function create_custom_testimonial_post_types() {
    register_post_type( 'testimonial',
        array(
            'labels' => array(
                'name' => 'Testimonial',
                'singular_name' => 'Testimonial',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Testimonial Slide',
                'edit' => 'Edit',
                'edit_item' => 'Edit Testimonial Slide',
                'new_item' => 'New Testimonial Slide',
                'view' => 'View',
                'view_item' => 'View Testimonial Slide',
                'search_items' => 'Search Testimonial Slide',
                'not_found' => 'No Testimonial Slide found',
                'not_found_in_trash' => 'No Testimonial Slide found in Trash'
            ),

            'public' => true,
            'menu_position' => 5,
            'supports' => array( 'title', 'editor', 'page-attributes'),
            'taxonomies' => array( '' ),
            'menu_icon' => '',
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'testimonials', 'with_front' => false ),
        )
    );
}


//Testimonial META BOX
function my_testimonial_admin() {
    add_meta_box( 'testimonial_meta_box',
        'Testimonial Author Info',
        'display_testimonial_meta_box',
        'testimonial', 'advanced', 'high'
    );
}

add_action( 'admin_init', 'my_testimonial_admin' );

function display_testimonial_meta_box( $test ) {
    global $post;
    $custom = get_post_custom($post->ID);
    $stars = $custom["num_stars"][0];
    ?>
    <table style="width: 100%;">
        <tr>
            <td style="width: 100%">Number of Stars</td>
        </tr>
        <tr>
            <td style="width: 100%">
                <table>
                    <tr>
                        <td>0 <input type="radio" name="num_stars" value="0" <?php if ( $stars == 0 ) echo 'checked="checked"'; ?>/></td>
                        <td>1 <input type="radio" name="num_stars" value="1" <?php if ( $stars == 1 ) echo 'checked="checked"'; ?>/></td>
                        <td>2 <input type="radio" name="num_stars" value="2" <?php if ( $stars == 2 ) echo 'checked="checked"'; ?>/></td>
                        <td>3 <input type="radio" name="num_stars" value="3" <?php if ( $stars == 3 ) echo 'checked="checked"'; ?>/></td>
                        <td>4 <input type="radio" name="num_stars" value="4" <?php if ( $stars == 4 ) echo 'checked="checked"'; ?>/></td>
                        <td>5 <input type="radio" name="num_stars" value="5" <?php if ( $stars == 5 ) echo 'checked="checked"'; ?>/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <input type="hidden" name="testimonial_flag" value="true" />
    </table>
<?php
}

function custom_fields_testimonial_update($post_id, $post ){
    if ( $post->post_type == 'testimonial' ) {
        if (isset($_POST['testimonial_flag'])) {
            update_post_meta($post_id, "num_stars", $_POST['num_stars']);
        }
    }
}

add_action( 'save_post', 'custom_fields_testimonial_update', 10, 2 );


function testimonials_func($atts) {
    extract( shortcode_atts( array(
        'id' => '',
        'class' => '',
        'posts_per_page' => -1
    ), $atts ));

    global $post;

    $testimonials = '';

    $testimonials .= '<div id="testimonialsCarousel" class="carousel slide" data-ride="carousel">';
        $testimonials .= '<div class="carousel-inner" role="listbox">';

            wp_reset_query();
            $args = array(
                'post_type' => 'testimonial',
                'posts_per_page' => $posts_per_page,
                'orderby' => 'menu_order',
                'order' => 'ASC'
            );
            $the_query = new WP_Query( $args );

            $counter = 0;
            while ( $the_query->have_posts() ) : $the_query->the_post();
                $stars = get_post_meta($post->ID, 'num_stars', true);

                $testimonials .= '<div class="carousel-item '.(($counter == 0)?"active":"").'">';
                    $testimonials .= '<div>';

                        $content = get_the_content();
                        if (strlen($content) > 400) {
                            // truncate content
                            $stringCut = substr($content, 0, 400);
                            // make sure it ends in a word so assassinate doesn't become ass...
                            $content = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
                        }

                        $testimonials .= apply_filters('the_content', $content);   

                        if($stars != '' && $stars != 0){
                            $testimonials .= '<div class="stars">';
                            for($i = 1; $i <= $stars; $i++){
                                $testimonials .= '<img src="'.get_stylesheet_directory_uri().'/admin/post_types/testimonials/images/testimonial-gold-stars.png" alt="Stars"/>';
                            }
                            $testimonials .= '</div>';
                        }
                        
                        $testimonials .= '<span>'.get_the_title().'</span>';

                    $testimonials .= '</div>';                   
                $testimonials .= '</div>';  //carousel-item END

            $counter ++;
            endwhile;
            wp_reset_query();

        $testimonials .= '</div>'; //carousel-inner END

        $testimonials .= '<a class="carousel-control-prev" href="#testimonialsCarousel" role="button" data-slide="prev">';
            $testimonials .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
            $testimonials .= '<span class="sr-only">Previous</span>';
        $testimonials .= '</a>';
        $testimonials .= '<a class="carousel-control-next" href="#testimonialsCarousel" role="button" data-slide="next">';
            $testimonials .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
            $testimonials .= '<span class="sr-only">Next</span>';
        $testimonials .= '</a>';

    $testimonials .= '</div>';

    return $testimonials;
}

add_shortcode( 'testimonials', 'testimonials_func' );