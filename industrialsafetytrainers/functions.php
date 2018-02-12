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
    wp_enqueue_script( 'industrial_safety_trainers_scripts', get_template_directory_uri().'/src/js/scripts.js',array('jquery'),false,true);
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
        'class' 		=> '',
        'href'          => ''
    ), $atts ) );


    $content = do_shortcode( shortcode_unautop( $content ) );
    $content = stripParagraphs($content);

    $return = '';
	$return .= '<'.$tag.' class=" '.$class.'" '.(($href !='')?"href=\"$href\" target=\"_blank\"":"").'>';
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



//Change WooCommerce Default Image
add_action( 'init', 'ist_fix_thumbnail' );
function ist_fix_thumbnail() {
  add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

    function custom_woocommerce_placeholder_img_src( $src ) {
        $upload_dir = wp_upload_dir();
        $uploads = untrailingslashit( $upload_dir['baseurl'] );
        $src = $uploads . '/2017/09/no-image.png';

        return $src;
    }
}

// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');








/**
 * Hide a free shipping option (instance #7) when the given shipping class is in the cart
 * Code snippets should be added to the functions.php file of your child theme
 *
 * @return array*/
/*
////************WORKING HIDE STATEMENT********************************/
  function hide_shipping_when_class_is_in_cart( $rates, $package ) {
      // shipping class IDs that need the method removed
      $shipping_classes = array('free-shipping');
      $if_exists = false;

      foreach( $package['contents'] as $key => $values ) {
          if( in_array( $values[ 'data' ]->get_shipping_class(), $shipping_classes ) )
              $if_exists = true;
      }

      if( $if_exists ){
         unset( $rates['free_shipping:2'] );
       }
       elseif( $if_exists == false ){
          unset( $rates['free_shipping:2'] );
        }

      return $rates;
  }
  add_filter( 'woocommerce_package_rates', 'hide_shipping_when_class_is_in_cart', 10, 2 );



function hide_shipping_when_class_is_in_cart_new( $rates, $package ) {
    // shipping class IDs that need the method removed
    $shipping_classes = array('free-shipping');
    $no_shipping_classes = array('no-shipping-class');
    $if_exists = false;
    $no_exists = false;

    foreach( $package['contents'] as $key => $values ) {
        if( in_array( $values[ 'data' ]->get_shipping_class(), $shipping_classes ) ){
            $if_exists = true;
          }
          elseif( in_array( $values[ 'data' ]->get_shipping_class(), $no_shipping_classes ) ){
            $no_exists = true;
            $if_exists = false;
          }
    }
    if( $if_exists == true ){
       reset( $rates['free_shipping:2'] );
       unset( $rates['jem_table_rate'] );
       }
     elseif( $if_exists == false ){
      unset( $rates['free_shipping:2'] );
      reset( $rates['jem_table_rate'] );
      }
    elseif( $if_exists == false && $no_exists = true){
     unset( $rates['free_shipping:2'] );
     reset( $rates['jem_table_rate'] );
   }

    return $rates;
}
add_filter( 'woocommerce_package_rates_new', 'hide_shipping_when_class_is_in_cart_new', 10, 2 );





//// TESTIMONIALS
include(TEMPLATEPATH.'/admin/post_types/testimonials/index.php');
include(TEMPLATEPATH.'/admin/post_types/in-the-community/index.php');
include(TEMPLATEPATH.'/admin/post_types/business-we-support/index.php');
include(TEMPLATEPATH.'/admin/post_types/slider/index.php');

//shortcodes
include(TEMPLATEPATH.'/admin/shortcodes/blog.php');
include(TEMPLATEPATH.'/admin/shortcodes/product-box.php');
include(TEMPLATEPATH.'/admin/shortcodes/woocommerce-categories.php');
include(TEMPLATEPATH.'/admin/shortcodes/course-details.php');
include(TEMPLATEPATH.'/admin/shortcodes/course-list.php');
include(TEMPLATEPATH.'/admin/shortcodes/course-search.php');

//OTHER FUNCTIONS
include(TEMPLATEPATH.'/admin/woocommerce/woocommerce.php');
