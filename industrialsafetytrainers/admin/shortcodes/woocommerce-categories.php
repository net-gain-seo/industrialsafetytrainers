<?php 

add_filter( 'get_terms_orderby', 'wps_get_terms_orderby', 10, 2 );
/**
 * Modifies the get_terms_orderby argument if orderby == include
 *
 * @param  string $orderby Default orderby SQL string.
 * @param  array  $args    get_terms( $taxonomy, $args ) arg.
 * @return string $orderby Modified orderby SQL string.
 */
function wps_get_terms_orderby( $orderby, $args ) {
  if ( isset( $args['orderby'] ) && 'include' == $args['orderby'] ) {
        $include = implode(',', array_map( 'absint', $args['include'] ));
        $orderby = "FIELD( t.term_id, $include )";
    }
    return $orderby;
}



function woocommerce_categories($atts){
	extract( shortcode_atts( array(
        'blog_id'   => 3,
        'parent'    => 0,
        'orderby'   => 'name',
        'include'   => ''
    ), $atts ));

    $current = get_current_blog_id();
    $currentBlogUrl = get_bloginfo('url');
    
    switch_to_blog($blog_id);
    $return = '';	

    if($include == ''){
        $include_array = array();
    }else{
        $include_array = explode(',', $include);
    }

    $args = array(
         'hide_empty'   => 0,
         'orderby'      => $orderby,
         'parent'       => $parent,
         'include'      => $include_array
    );


    $terms = get_terms('product_cat',$args);
    foreach ($terms as $term) {

        $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'posts_per_page'        => -1,
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'product_cat',
                    'field'         => 'term_id',
                    'terms'         =>  $term->term_id,
                    'operator'      => 'IN'
                )
            )
        );
        $products = get_posts($args);

        if(!empty($products)){
            $return .= '<div>';
                 $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
                    $image = wp_get_attachment_url( $thumbnail_id );
                    if ( $image ) {
                        $return .= '<img src="' . $image . '" alt="' . $term->name . '" />';
                    }
                 $return .= '<div>';
                    $return .= '<h3>'.$term->name.'</h3>';
                    $return .= '<a href="'.$currentBlogUrl.'/safety-training-course/?course='.$products[0]->post_name.'" class="btn btn-primary">View Courses</a>';
                 $return .= '</div>';
            $return .= '</div>';
        }
    }

    switch_to_blog($current);

    return $return;

}

add_shortcode('woocommerce_categories','woocommerce_categories');