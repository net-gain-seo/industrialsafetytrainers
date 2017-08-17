<?php 

function blog_feed(){
	extract( shortcode_atts( array(
        'posts_per_page' => -3
    ), $atts ));

	//QUERY POSTS
	wp_reset_query();
    $args = array(
        'posts_per_page' => $posts_per_page
    );
    $the_query = new WP_Query( $args );


    $return = '';

    //LOOP AND DISPLAY
    while ( $the_query->have_posts() ) : $the_query->the_post();
    	$return .= '<div class="post" onclick="window.location.href=\''.get_the_permalink().'\'">';
    		$return .= '<span class="post-date">'.get_the_date('M d, Y').'</span>';
			$return .= '<span class="post-title">'.get_the_title().'</span>';
    		$return .= '<p>'.get_the_excerpt().'</p>';
    		$return .= '<a class="post-read-more" href="'.get_the_permalink().'">Read More</a>';
    	$return .= '</div>';
    endwhile;

    wp_reset_query();

    return $return;

}

add_shortcode('blog_feed','blog_feed');