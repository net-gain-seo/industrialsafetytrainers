<?php

add_action( 'init', 'create_custom_slider_post_types' );

//CUSTOM POST TYPE
function create_custom_slider_post_types() {
    register_post_type( 'slider',
        array(
            'labels' => array(
                'name' => 'Sliders',
                'singular_name' => 'Slider',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Slider',
                'edit' => 'Edit',
                'edit_item' => 'Edit Slider',
                'new_item' => 'New Slider',
                'view' => 'View',
                'view_item' => 'View Sliders',
                'search_items' => 'Search Sliders',
                'not_found' => 'No Sliders found',
                'not_found_in_trash' => 'No Sliders found in Trash'
            ),
            'public' => true,
            'menu_position' => 5,
            'supports' => array( 'title', 'editor','thumbnail'),
            'taxonomies' => array( '' ),
            'menu_icon' => '',
            'has_archive' => false,
            'rewrite' => array( 'slug' => 'sliders', 'with_front' => false ),
        )
    );
}

function slider_wp_enqueue_scripts(){
    wp_register_style( 'slick-style', get_stylesheet_directory_uri().'/admin/post_types/slider/css/slick.css');
    wp_register_script( 'slick-script', get_stylesheet_directory_uri().'/admin/post_types/slider/js/slick.min.js',array('jquery'));
    wp_register_script( 'slick-slider', get_stylesheet_directory_uri().'/admin/post_types/slider/js/slick-slider.js',array('slick-script'));
}
add_action( 'wp_enqueue_scripts', 'slider_wp_enqueue_scripts' );



function slider(){

    wp_enqueue_style( 'slick-style');
    wp_enqueue_script( 'slick-script');
    wp_enqueue_script( 'slick-slider');

    extract( shortcode_atts( array(
        'id' => '',
        'class' => ''
    ), $atts ));

    global $post;
    $return = '';


    wp_reset_query();
    $args = array(
        'post_type' => 'slider',
        'posts_per_page' => $posts_per_page,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    $the_query = new WP_Query( $args );

    $return .= '<div class="slider">';
        while ( $the_query->have_posts() ) : $the_query->the_post();
        $content = do_shortcode( get_the_content());
        $content = apply_filters('the_content',$content);


        $return .= '<div class="banner-section">';
            $return .= '<div class="banner-content d-flex justify-content-center align-items-stretch">';
                $return .= '<div>';
                    $return .= '<div class="fifty-percent-section right">';
                        $return .= '<h2>'.get_the_title().'</h2>';

                        
                        $return .= $content;
                    $return .= '</div>';
                $return .= '</div>';
                $return .= '<div></div>';
            $return .= '</div>';
            $return .= get_the_post_thumbnail();
        $return .= '</div>';
        endwhile;

    $return .= '</div>';

    wp_reset_query();

    return $return;
}
add_shortcode( 'slider', 'slider' );