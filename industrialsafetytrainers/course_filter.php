<?php

//Set up ajax function
add_action('wp_ajax_course_filter', 'course_filter');
add_action('wp_ajax_nopriv_course_filter', 'course_filter');

function course_filter(){
    //Get wordpress
    global $wpdb;
    $wpdb->show_errors();
    //Do ajax function things
    $months = $_POST['months'];
    $locations = $_POST['locations'];
    $parent = $_POST['parent'];

/*
    $mquery = '123456';
    $query = 'SELECT wp_3_postmeta.*, wp_3_posts.*,wp2.meta_value AS price,wp3.meta_value AS `time`,wp4.meta_value AS location FROM wp_3_postmeta ';
    $query .= 'JOIN wp_3_posts ON (wp_3_postmeta.post_id = wp_3_posts.ID) ';
    $query .= 'JOIN wp_3_postmeta wp2 ON (wp_3_postmeta.post_id = wp2.post_id AND wp2.meta_key = "_price") ';
    $query .= 'JOIN wp_3_postmeta wp3 ON (wp_3_postmeta.post_id = wp2.post_id AND wp2.meta_key = "attribute_pa_time") ';
    $query .= 'JOIN wp_3_postmeta wp4 ON (wp_3_postmeta.post_id = wp2.post_id AND wp2.meta_key = "attribute_pa_location") ';
    $query .= 'WHERE ';
    $query .= 'wp_3_postmeta.meta_key LIKE "attribute_pa_date" ';
    if(sizeof($months) > 0 && sizeof($locations) == 0) {
        $count = 0;
        foreach($months as $month) {
            if($count == 0) {
                $query .= 'AND (wp_3_postmeta.meta_value LIKE "%-'.$month.'-%" ';
            }
            else {
                $query .= ' OR wp_3_postmeta.meta_value LIKE "%-'.$month.'-%" ';
            }
            $count++;
        }
        $query .= ')';
        $rows = $wpdb->get_results($query, ARRAY_A);
    }
*/
    if(sizeof($months) > 0 && sizeof($locations) == 0) {
        $queryArray = [];
        $queryArray['relation'] = 'OR';
        foreach($months as $month) {
            $arr = array(
                'key' => 'attribute_pa_date',
                'value' => '-'.$month.'-',
                'compare' => 'LIKE'
            );
            array_push($queryArray,$arr);
        }

        switch_to_blog(3);
        $args = array(
            'post_type'     => 'product_variation',
            'post_status'   => array('publish' ),
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'post_parent'   => $parent,
            'meta_query'    => array(
                $queryArray
            )
        );
    }
    else if(sizeof($months) > 0 && sizeof($locations) > 0) {
        $monthArray = [];
        $locationArray = [];
        $monthArray['relation'] = 'OR';
        $locationArray['relation'] = 'OR';
        foreach($months as $month) {
            $arr = array(
                'key' => 'attribute_pa_date',
                'value' => '-'.$month.'-',
                'compare' => 'LIKE'
            );
            array_push($monthArray,$arr);
        }
        foreach($locations as $location) {
            $arr = array(
                'key' => 'attribute_pa_location',
                'value' => $location,
                'compare' => 'LIKE'
            );
            array_push($locationArray,$arr);
        }

        switch_to_blog(3);
        $args = array(
            'post_type'     => 'product_variation',
            'post_status'   => array('publish' ),
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'post_parent'   => $parent,
            'meta_query'    => array(
                'relation' => "AND",
                $monthArray,
                $locationArray
            )
        );
    }
    else if(sizeof($months) == 0 && sizeof($locations) > 0) {
        $locationArray = [];
        $locationArray['relation'] = 'OR';
        foreach($locations as $location) {
            $arr = array(
                'key' => 'attribute_pa_location',
                'value' => $location,
                'compare' => 'LIKE'
            );
            array_push($locationArray,$arr);
        }

        switch_to_blog(3);
        $args = array(
            'post_type'     => 'product_variation',
            'post_status'   => array('publish' ),
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'post_parent'   => $parent,
            'meta_query'    => array(
                $locationArray
            )
        );
    }
    else {
        switch_to_blog(3);
        $args = array(
            'post_type'     => 'product_variation',
            'post_status'   => array('publish'),
            'numberposts'   => -1,
            'orderby'       => 'menu_order',
            'order'         => 'asc',
            'post_parent'   => $parent
        );
    }

    $p = get_posts($args);
    $return = '';
    foreach ($p as $key => $variation){
        // get variation ID
        $variation_ID = $variation->ID;

        // get variations meta
        $product_variation = new WC_Product_Variation( $variation_ID );
        //print_r($product_variation);
        $variation_price = $product_variation->get_price_html();
        $variation_description = $product_variation->get_description();
        $variation_stock = $product_variation->get_stock_quantity();

        if($variation_stock > 0) {
            $return .= '<tr class="course-info-'.strtotime(get_post_meta( $variation_ID, 'attribute_pa_date', true )).'" data-timestamp="'.strtotime(get_post_meta( $variation_ID, 'attribute_pa_date', true )).'">';
                $return .= '<td>'.get_post_meta( $variation_ID, 'attribute_pa_location', true ).'</td>';
                $return .= '<td>'.date('M j, Y',strtotime(get_post_meta( $variation_ID, 'attribute_pa_date', true ))).'</td>';
                $return .= '<td>'.get_post_meta( $variation_ID, 'attribute_pa_time', true ).'</td>';
                $return .= '<td>'.$variation_price.'</td>';
                $return .= '<td><input type="number" min="0" max="'.$variation_stock.'" placeholder="0" name="course_qty" data-id="'.$variation_ID.'"/></td>';
                $return .= '<td>'.$variation_description.'</td>';
                $return .= '<td><a class="'.$variation_ID.'_url btn btn-primary" href="'.get_bloginfo('url').'/?add-to-cart='.$current_product[0]->ID.'&variation_id='.$variation_ID.'&quantity=0" target="_blank">Purchase</a></td>';
            $return .= '</tr>';
        }
    }

    switch_to_blog(1);

    echo json_encode(array("data"=>$return,"success"=>"success"));
    die;
}