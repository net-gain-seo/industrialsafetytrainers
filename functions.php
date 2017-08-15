<?php 


// ENQUEUE STYLES AND SCRIPTS
function industrial_safety_trainers_scripts() {
    wp_enqueue_style( 'style-name', get_stylesheet_directory_uri().'/src/css/main.css',array('bootstrap') );
    wp_enqueue_script( 'site-scripts', get_stylesheet_directory_uri().'/src/js/scripts.js' );
}
add_action( 'wp_enqueue_scripts', 'industrial_safety_trainers_scripts' );





//BLOG SIDEBAR
add_action( 'widgets_init', 'industrial_safety_trainers_widgets_init' );
function industrial_safety_trainers_widgets_init(){
    register_sidebar( array(
        'name' => 'Blog Sidebar',
        'id' => 'blog-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widgettitle">',
        'after_title'   => '</h3>',

    ));
}


//POST THUMBNAIL
add_image_size('blog-image',2000,375,true);



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




//PDF DOWNLOAD SHORTCODE
function pdf_download($atts,$content){
    extract( shortcode_atts( array(
        'class'         => '',
        'title'         => 'Title',
        'link'          => '#'
    ), $atts ) );

    $return = '';
    $return .= '<a href="'.$link.'" class="pdf-download d-flex align-items-center '.$class.'">';
        $return .= '<img src="'.get_stylesheet_directory_uri().'/assets/images/pdf-download.png" />';
        $return .= '<span class="pdf-download-download">Download</span>';
        $return .= '<span class="pdf-download-title">'.$title.'</span>';
    $return .= '</a>';

    return $return;
}
add_shortcode('pdf_download','pdf_download');



//// TESTIMONIALS
include(STYLESHEETPATH.'/admin/post_types/testimonials/index.php');
include(STYLESHEETPATH.'/admin/post_types/in-the-community/index.php');
include(STYLESHEETPATH.'/admin/post_types/business-we-support/index.php');
include(STYLESHEETPATH.'/admin/post_types/slider/index.php');

//shortcodes
include(STYLESHEETPATH.'/admin/shortcodes/blog.php');