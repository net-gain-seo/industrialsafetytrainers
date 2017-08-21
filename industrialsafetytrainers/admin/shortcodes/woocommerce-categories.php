<?php 

function woocommerce_categories($atts){
	extract( shortcode_atts( array(
        'blog_id'   => 3,
        'parent'    => 0,
        'orderby'   => 'name'
    ), $atts ));

    $current = get_current_site();
    switch_to_blog($blog_id);
    $return = '';	

    $args = array(
         'hide_empty'   => 0,
         'orderby'      => $orderby,
         'parent'       => $parent
    );
    $terms = get_terms('product_cat',$args);
    foreach ($terms as $term) {
        $return .= '<div>';
             //$return .= '<img alt="'.$cat.'" src="'.$image.'" />';
             $return .= '<div>';
                 $return .= '<h3>'.$term->name.'</h3>';
                 $return .= '<a href="'.$term->slug.'" class="btn btn-primary">VIEW COURSES</a>';
             $return .= '</div>';
        $return .= '</div>';
    }

    switch_to_blog($current->id);

    return $return;

}

add_shortcode('woocommerce_categories','woocommerce_categories');