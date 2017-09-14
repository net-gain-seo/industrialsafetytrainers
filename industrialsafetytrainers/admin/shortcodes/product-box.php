<?php 

function product_box($atts){
	extract( shortcode_atts( array(
        'image' => '',
        'title' => '',
        'link' => '',
        'button_text' => 'View Courses'
    ), $atts ));

	//QUERY POSTS
	wp_reset_query();
    $args = array(
        'posts_per_page' => $posts_per_page
    );
    $the_query = new WP_Query( $args );


    $return = '';

    $return .= '<div>';
         $return .= '<img alt="'.$title.'" src="'.$image.'" />';
         $return .= '<div>';
             $return .= '<h3>'.$title.'</h3>';
             $return .= '<a href="'.$link.'" class="btn btn-primary">'.$button_text.'</a>';
         $return .= '</div>';
    $return .= '</div>';

    return $return;

}

add_shortcode('product_box','product_box');