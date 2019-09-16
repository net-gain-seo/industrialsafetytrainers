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
    
    switch_to_blog(1);
    $return = '';	

    if($include == ''){
        $include_array = array();
    }else{
        $include_array = explode(',', $include);
    }


    if(isset($_GET['category'])){
        $term = get_term_by('slug',$_GET['category'],'product_cat');
        $parent = $term->term_id;
    }

    $args = array(
         'hide_empty'   => 0,
         'orderby'      => $orderby,
         'parent'       => $parent,
         'include'      => $include_array
    );

    $terms = get_terms('product_cat',$args);
    //If child categories list them else show products
    if(count($terms) > 0){
        foreach ($terms as $term) {
            $return .= '<div>';
                 $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
                    $image = wp_get_attachment_url( $thumbnail_id );
                    if ( $image ) {
                        $return .= '<img src="' . $image . '" alt="' . $term->name . '" />';
                    }
                 $return .= '<div>';
                    $return .= '<h3>'.$term->name.'</h3>';

                    $category_url_overwrite = get_term_meta($term->term_id,'category_url_overwrite',true);
                    if($category_url_overwrite != ''){
                        $catUrl = $category_url_overwrite;
                    }else{
                        $catUrl = $currentBlogUrl.'/on-site-courses/?category='.$term->slug;
                    }

                    $return .= '<a href="'.$catUrl.'" class="btn btn-primary">View Course</a>';
                 $return .= '</div>';
            $return .= '</div>';
        }
    }else{
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
            foreach($products as $product){
                //print_r($product);
                $return .= '<div>';
                     $return .= get_the_post_thumbnail( $product->ID);
                     $return .= '<div>';
                        $return .= '<h3>'.$product->post_title.'</h3>';
                        $return .= '<a href="'.$currentBlogUrl.'/safety-training-course/?course='.$product->post_name.'" class="btn btn-primary">View Course</a>';
                     $return .= '</div>';
                $return .= '</div>';
            }
        }
    }
    switch_to_blog($current);

    return $return;

}

add_shortcode('woocommerce_categories','woocommerce_categories');





