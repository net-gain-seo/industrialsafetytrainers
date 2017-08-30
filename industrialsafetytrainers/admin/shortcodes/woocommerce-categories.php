<?php 

function woocommerce_categories($atts){
	extract( shortcode_atts( array(
        'blog_id'   => 3,
        'parent'    => 0,
        'orderby'   => 'name'
    ), $atts ));

    $current = get_current_blog_id();
    $currentBlogUrl = get_bloginfo('url');

    switch_to_blog($blog_id);
    $return = '';	

    $args = array(
         'hide_empty'   => 0,
         'orderby'      => $orderby,
         'parent'       => $parent
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
                 //$return .= '<img alt="'.$cat.'" src="'.$image.'" />';
                 $return .= '<div>';
                     $return .= '<h3>'.$term->name.'</h3>';
                     $return .= '<a href="'.$currentBlogUrl.'/safety-training-course/?course='.$products[0]->post_name.'" class="btn btn-primary">VIEW COURSES</a>';
                 $return .= '</div>';
            $return .= '</div>';
        }
    }

    switch_to_blog($current);

    return $return;

}

add_shortcode('woocommerce_categories','woocommerce_categories');