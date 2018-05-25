<?php




function course_details($atts){
	extract( shortcode_atts( array(
        'blog_id'   => 3,
        'parent'    => 0,
        'orderby'   => 'name',
        'single_category'  => '',
        'slug'      => 'safety-training-course'
    ), $atts ));

    $current = get_current_blog_id();
    $currentBlogUrl = get_bloginfo('url');
    switch_to_blog($blog_id);



    $return = '';
    $category_name = '';

    $return .= '<div class="container-fluid">';
        $return .= '<div class="container course-category-details">';
            // GET CURRENT PRODUCT IF GET VARIABLE SET
            if(isset($_GET['course'])){
                $args = array(
                    'name'        => $_GET['course'],
                    'post_type'   => 'product',
                    'post_status' => 'publish',
                    'numberposts' => 1
                );
                $current_product = get_posts($args);


                $return .= '<div id="course-container" class="course-container">';

                    /*
                    $return .= '<div class="course-header">';
                         $return .= '<div class="course-header-bg"></div>';

                        $return .= '<div class="course-header-details">';
                            $return .= '<h3>'.$current_product[0]->post_title.' Course Summary</h3>';
                            $return .= '<div class="course-type-container">';
                                $_public_course = get_post_meta($current_product[0]->ID,'_public_course',true);
                                $_private_course = get_post_meta($current_product[0]->ID,'_private_course',true);
                                $_online_course = get_post_meta($current_product[0]->ID,'_online_course',true);



                            $return .= '</div>';
                        $return .= '</div>';
                    $return .= '</div>';
                    */

                    $return .= '<div class="course-detail">';
                        //$return .= '<h3 class="h2 mb-0">'.$category_name.'</h3>';
                        //$return .= '<h2 class="h3">'.$current_product[0]->post_title.'</h2>';

                        $return .= '<div style="float: right;margin-left: 20px;margin-bottom: 20px;">'.get_the_post_thumbnail($current_product[0]->ID).'</div>';

                        $return .= apply_filters('the_content',$current_product[0]->post_content);
                    $return .= '</div>';


                    $return .= '<div class="accordion" role="tablist" aria-multiselectable="true">';


                        // COURSE SPECS
                        $course_specs = get_post_meta($current_product[0]->ID,'course_specs',true);
                        if($course_specs != ''){
                            $return .= '<div class="card">';
                                $return .= '<div class="card-header" role="tab">';
                                    $return .= '<h5 class="mb-0">';
                                        $return .= '<a data-toggle="collapse" data-parent="#accordion" href="#courseSpecs" aria-expanded="true" aria-controls="courseSpecs">About This Course</a>';
                                    $return .= '</h5>';
                                $return .= '</div>';

                                $return .= '<div id="courseSpecs" class="accordion-card-contents collapse show" role="tabpanel">';
                                    $return .= '<div class="card-block">';

                                        $return .= apply_filters('the_content',$course_specs);
                                    $return .= '</div>';
                                $return .= '</div>';
                            $return .= '</div>';
                        }




                    $return .= '</div>';

                    // get up sells
                    $product = new WC_Product($current_product[0]->ID);
                    $upsells = $product->get_upsells();

                    if(!empty($upsells)){

                        $args = array(
                            'post_type' => 'product',
                            'ignore_sticky_posts' => 1,
                            'no_found_rows' => 1,
                            'posts_per_page' => $posts_per_page,
                            'orderby' => $orderby,
                            'post__in' => $upsells,
                            'post__not_in' => array($product->id),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field' => 'term_id',
                                    'terms' => $parent,
                                )
                            )
                        );

                        $upsells = get_posts($args);
                        if (!empty($upsells)){
                            $return .= '<h3 style="margin-top:20px;">Do you also need..</h3>';
                            $return .= '<ul class="category-list"><li>';
                             $return .= '<ul>';
                            foreach($upsells as $upsell){
                               $return .= '<li><a href="'.$currentBlogUrl.'/'.$slug.'/?course='.$upsell->post_name.'">'.$upsell->post_title.'</a></li>';
                            }
                            $return .= '</ul></li></ul>';
                        }
                    }


                $return .= '</div>';

            }

            $return .= '<div class="category-container">';
            $return .= '<div class="category-background"></div>';
                $_public_course = get_post_meta($current_product[0]->ID,'_public_course',true);
                $_private_course = get_post_meta($current_product[0]->ID,'_private_course',true);
                $_online_course = get_post_meta($current_product[0]->ID,'_online_course',true);

                // COURSE OFFERED
                $return .= '<div class="course-detail-section">';
                    $return .= '<h5>This course is offered</h5>';
                    $return .= '<div class="courseType">';
                        if($_public_course == 'yes'){ $return .= '<span class="course-type-public"></span>'; }
                        if($_private_course == 'yes'){ $return .= '<span class="course-type-private"></span>'; }
                        if($_online_course == 'yes'){ $return .= '<span class="course-type-online"></span>'; }
                    $return .= '</div>';
                $return .= '</div>';


                // COURSE COST OUTLINE
                $return .= '<div class="course-detail-section">';
                    $cost_outline = get_post_meta($current_product[0]->ID,'cost_outline',true);

                    if($cost_outline != ''){
                        $return .= apply_filters('the_content',$cost_outline);
                    }
                $return .= '</div>';

                // INFO SHEET
                $return .= '<div class="course-detail-section">';
                    $info_sheet = get_post_meta($current_product[0]->ID,'info_sheet',true);
                    if($info_sheet != ''){
                    $return .= '<a href="'.$info_sheet.'" title="'.$current_product[0]->post_title.' Info Sheet" class="pdf-download d-flex align-items-center " target="_blank"><img src="https://thesafetybus.com/wp-content/themes/industrialsafetytrainers/assets/images/pdf-download.png"><span class="pdf-download-download">Info Sheet</span></a>';
                    }
                $return .= '</div>';

                // REGISTRATION
                $return .= '<div class="course-detail-section callToActions">';
                    $_product = wc_get_product( $current_product[0]->ID );

                    if($_public_course == 'yes'){
                        $return .= '<a href="'.get_site_url($current).'/safety-training-course-public-dates/?course='.$current_product[0]->post_name.'" class="btn btn-danger">VIEW PUBLIC DATES</a>';
                    }

                    if($_private_course == 'yes'){
                        $return .= '<button type="button" class="btn btn-primary " role="button" data-toggle="modal" data-target="#requestInformationModal">BOOK COURSE</button>';
                    }


                    $demo_url = get_post_meta($current_product[0]->ID,'demo_url',true);
                    if($demo_url != ''){
                        $return .= '<a href="'.$demo_url.'" target="_blank" class="btn btn-warning">DEMO REQUEST</a>';
                    }

                    if($_online_course == 'yes'){
                        if( $_product->is_type( 'external' ) ){
                            $_product_url = get_post_meta($current_product[0]->ID,'_product_url',true);

                            $return .= '<div><a href="'.$_product_url.'" class="btn btn-danger" target="_blank">PURCHASE COURSE</a></div>';
                        }else{
                            $return .= '<div><a href="'.$current_product[0]->guid.'" class="btn btn-danger">PURCHASE COURSE</a></div>';
                        }

                    }
               $return .= '</div>';


                // WHAT IS INCLUDED
                $return .= '<div class="course-detail-section">';
                    $course_outline = get_post_meta($current_product[0]->ID,'course_outline',true);
                    if($course_outline != ''){
                        $return .= apply_filters('the_content',$course_outline);
                    }
                $return .= '</div>';


            $return .= '</div>';

        $return .= '</div>';
    $return .= '</div>';



    switch_to_blog($current);
    return $return;
}

add_shortcode('course_details','course_details');


function course_public_dates(){
    extract( shortcode_atts( array(
        'blog_id'   => 3,
        'parent'    => 0,
        'orderby'   => 'name',
        'single_category'  => '',
        'slug'      => 'safety-training-course'
    ), $atts ));

    $current = get_current_blog_id();
    $currentBlogUrl = get_bloginfo('url');
    switch_to_blog($blog_id);



    $return = '';

    $return .= '<div class="container-fluid">';
        $return .= '<div class="container">';
            // GET CURRENT PRODUCT IF GET VARIABLE SET
            if(isset($_GET['course'])){
                $args = array(
                    'name'        => $_GET['course'],
                    'post_type'   => 'product',
                    'post_status' => 'publish',
                    'numberposts' => 1
                );
                $current_product = get_posts($args);


                $args = array(
                    'post_type'     => 'product_variation',
                    'post_status'   => array('publish' ),
                    'numberposts'   => -1,
                    'orderby'       => 'menu_order',
                    'order'         => 'asc',
                    'post_parent'   => $current_product[0]->ID
                );

                /*
                $args = array(
                    'post_type'     => 'product_variation',
                    'post_status'   => array('publish' ),
                    'numberposts'   => -1,
                    'meta_key'      => 'attribute_pa_date',
                    'orderby'       => 'meta_value',
                    'order'         => 'asc',
                    'post_parent'   => $current_product[0]->ID
                );
                */

                $variations = get_posts( $args );

                $return .= '<div class="row">';
                    $return .= '<div class="col col-3">';
                        $return .= '<h3>Month</h3>';
                        $return .= '<ul class="courseCategories">';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="01" data-parent="'.$current_product[0]->ID.'"/> January</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="02" data-parent="'.$current_product[0]->ID.'"/> February</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="03" data-parent="'.$current_product[0]->ID.'"/> March</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="04" data-parent="'.$current_product[0]->ID.'"/> April</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="05" data-parent="'.$current_product[0]->ID.'"/> May</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="06" data-parent="'.$current_product[0]->ID.'"/> June</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="07" data-parent="'.$current_product[0]->ID.'"/> July</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="08" data-parent="'.$current_product[0]->ID.'"/> August</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="09" data-parent="'.$current_product[0]->ID.'"/> September</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="10" data-parent="'.$current_product[0]->ID.'"/> October</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="11" data-parent="'.$current_product[0]->ID.'"/> November</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_month" value="12" data-parent="'.$current_product[0]->ID.'"/> December</label></li>';
                        $return .= '</ul>';

                        $return .= '<h3>Location</h3>';
                        $return .= '<ul class="courseCategories">';
                            $return .= '<li><label><input type="checkbox" name="filter_location" value="Barrie" data-parent="'.$current_product[0]->ID.'"/> Barrie</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_location" value="Newmarket" data-parent="'.$current_product[0]->ID.'"/> Newmarket</label></li>';
                            $return .= '<li><label><input type="checkbox" name="filter_location" value="Another Place" data-parent="'.$current_product[0]->ID.'"/> Another Place</label></li>';
                        $return .= '</ul>';
                    $return .= '</div>';
                    $return .= '<div class="col col-9">';

                        $return .= '<table class="table">';
                            $return .= '<thead>';
                                $return .= '<tr>';
                                    $return .= '<th>Location</th>';
                                    $return .= '<th class="sort-date">Date ◆</th>';
                                    $return .= '<th>Time</th>';
                                    $return .= '<th>Price</th>';
                                    $return .= '<th>Notes</th>';
                                    $return .= '<th>Quantity</th>';
                                    $return .= '<th>Purchase</th>';
                                $return .= '</tr>';
                            $return .= '</thead>';

                            $return .= '<tbody class="course_list">';
                                foreach ($variations as $key => $variation){
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
                                            $return .= '<td>'.$variation_description.'</td>';
                                            $return .= '<td><input type="number" min="0" max="'.$variation_stock.'" placeholder="0" name="course_qty" data-id="'.$variation_ID.'"/></td>';
                                            $return .= '<td><a class="'.$variation_ID.'_url btn btn-primary" href="'.get_bloginfo('url').'/?add-to-cart='.$current_product[0]->ID.'&variation_id='.$variation_ID.'&quantity=0" target="_blank">Purchase</a></td>';
                                        $return .= '</tr>';
                                    }
                                }

                            $return .= '</tbody>';
                        $return .= '</table>';
                        $return .= '<div class="loader">Loading...</div>';

                    $return .= '</div>';
                $return .= '</div>';


            }

        $return .= '</div>';
    $return .= '</div>';



    switch_to_blog($current);
    return $return;
}
add_shortcode('course_public_dates','course_public_dates');


function course_category_list($atts){

    extract( shortcode_atts( array(
        'blog_id'           => 3,
        'parent'            => 0,
        'single_category'   => '',
        'slug'              => 'safety-training-course',
        'current_blog_url'    => ''
    ), $atts ));



    $current = get_current_blog_id();
    switch_to_blog($blog_id);


    $args = array(
         'hide_empty'       => 0,
         'orderby'          => $orderby,
         'parent'           => $parent
    );




    $terms = get_terms('product_cat',$args);

        $return .= '<ul class="category-list">';

        if($single_category != 'true'){
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


                    $isActive = false;
                    foreach($products as $product){
                        if(isset($_GET['course']) && $_GET['course'] == $product->post_name){
                            $isActive = true;
                            $category_name = $term->name;
                            break;
                        }
                    }

                    $return .= '<li>';
                         $return .= '<a data-toggle="collapse" data-target="#product-category-'.$term->slug.'" href="#" class="'.((!$isActive)?"collapsed":"").'">'.$term->name.'</a>';
                        if(!empty($products)){
                            $return .= '<ul id="product-category-'.$term->slug.'" class="'.((!$isActive)?"collapse":"").'">';
                            foreach($products as $product){
                                $return .= '<li><a class="'.((isset($_GET['course']) && $_GET['course'] == $product->post_name)?"active":"").'" href="'.$current_blog_url.'/'.$slug.'/?course='.$product->post_name.'">'.$product->post_title.'</a></li>';
                            }
                            $return .= '</ul>';
                        }
                    $return .= '</li>';
                }
            }
        }else{

            $args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'posts_per_page'        => -1,
                'orderby'               => 'title',
                'order'                 => 'ASC',
                'tax_query'             => array(
                    array(
                        'taxonomy'      => 'product_cat',
                        'field'         => 'term_id',
                        'terms'         =>  $parent,
                        'operator'      => 'IN'
                    )
                )
            );
            $products = get_posts($args);
            if(!empty($products)){
                $isActive = true;
                $category_name = $term->name;

                $return .= '<li>';
                     //$return .= '<a data-toggle="collapse" data-target="#product-category-'.$term->slug.'" href="#" class="'.((!$isActive)?"collapsed":"").'">+ '.$term->name.'</a>';
                    if(!empty($products)){
                        $return .= '<ul id="product-category-'.$term->slug.'" class="'.((!$isActive)?"collapse":"").'">';
                        foreach($products as $product){
                            $return .= '<li><a class="'.((isset($_GET['course']) && $_GET['course'] == $product->post_name)?"active":"").'" href="'.$current_blog_url.'/'.$slug.'/?course='.$product->post_name.'">'.$product->post_title.'</a></li>';
                        }
                        $return .= '</ul>';
                    }
                $return .= '</li>';
            }
        }
    $return .= '</ul>';


    return $return;

    switch_to_blog($current);

}
add_shortcode('course_category_list','course_category_list');
