<?php 


// ENQUEUE STYLES AND SCRIPTS
function industrial_safety_trainers_scripts() {
    wp_enqueue_style( 'style-name', get_stylesheet_directory_uri().'/src/css/main.css',array('bootstrap') );
    wp_enqueue_script( 'site-scripts', get_stylesheet_directory_uri().'/src/js/scripts.js' );
}
add_action( 'wp_enqueue_scripts', 'industrial_safety_trainers_scripts' );




//ELEMENT SHORTCODE
function element($atts,$content){
	extract( shortcode_atts( array(
		'tag'			=> 'div',
        'class' 		=> ''
    ), $atts ) );


    $content = do_shortcode( shortcode_unautop( $content ) );
    $content = stripParagraphs($content);

    $return = '';
	$return .= '<'.$tag.' class=" '.$class.'">';
        $return .= $content;
    $return .= '</'.$tag.'>';

	return $return;
}
add_shortcode('el_1','element');
add_shortcode('el_2','element');
add_shortcode('el_3','element');