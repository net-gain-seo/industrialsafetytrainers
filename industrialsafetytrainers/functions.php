<?php 

//ADD THUMBNAIL SUPPORT
add_theme_support('post-thumbnails');

//REGISTER MAIN MENU
add_action('init','register_site_menus');
function register_site_menus(){
    register_nav_menus(array(
        'main' => 'Main Menu'
    ));
}







// ENQUEUE STYLES AND SCRIPTS
function industrial_safety_trainers_scripts() {
    wp_enqueue_style( 'industrial_safety_trainers_style', get_template_directory_uri().'/src/css/main.css',array('bootstrap') );
    wp_enqueue_script( 'industrial_safety_trainers_scripts', get_template_directory_uri().'/src/js/scripts.js' );
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

    register_sidebar( array(
        'name' => 'Footer 1',
        'id' => 'footer-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widgettitle">',
        'after_title'   => '</h5>',
    ));
    register_sidebar( array(
        'name' => 'Footer 2',
        'id' => 'footer-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widgettitle">',
        'after_title'   => '</h5>',
    ));
    register_sidebar( array(
        'name' => 'Footer 3',
        'id' => 'footer-3',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widgettitle">',
        'after_title'   => '</h5>',
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
    $return .= '<a href="'.$link.'" class="pdf-download d-flex align-items-center '.$class.'" target="_blank">';
        $return .= '<img src="'.get_template_directory_uri().'/assets/images/pdf-download.png" />';
        $return .= '<span class="pdf-download-download">Download</span>';
        $return .= '<span class="pdf-download-title">'.$title.'</span>';
    $return .= '</a>';

    return $return;
}
add_shortcode('pdf_download','pdf_download');



//// TESTIMONIALS
include(TEMPLATEPATH.'/admin/post_types/testimonials/index.php');
include(TEMPLATEPATH.'/admin/post_types/in-the-community/index.php');
include(TEMPLATEPATH.'/admin/post_types/business-we-support/index.php');
include(TEMPLATEPATH.'/admin/post_types/slider/index.php');

//shortcodes
include(TEMPLATEPATH.'/admin/shortcodes/blog.php');
include(TEMPLATEPATH.'/admin/shortcodes/product-box.php');
include(TEMPLATEPATH.'/admin/shortcodes/woocommerce-categories.php');
include(TEMPLATEPATH.'/admin/shortcodes/course-list.php');

//OTHER FUNCTIONS
include(TEMPLATEPATH.'/admin/woocommerce/woocommerce.php');