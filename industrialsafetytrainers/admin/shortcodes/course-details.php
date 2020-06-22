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
    switch_to_blog(1);



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
                    $_public_course = get_post_meta($current_product[0]->ID,'_public_course',true);
                    $_private_course = get_post_meta($current_product[0]->ID,'_private_course',true);
                    $_online_course = get_post_meta($current_product[0]->ID,'_online_course',true);
                    $_virtual_course = get_post_meta($current_product[0]->ID,'_virtual_course',true);

                    $public_product_link = get_post_meta($current_product[0]->ID,'public_product_link',true);
                    $virtual_product_link = get_post_meta($current_product[0]->ID,'virtual_product_link',true);

                        // COURSE SPECS
                        $course_specs = get_post_meta($current_product[0]->ID,'course_specs',true);
                        if($course_specs != ''){
                            $return .= '<div class="card-group course-specs">';
                                $return .= '<div class="card">';
                                    $return .= '<div class="card-header" role="tab">';
                                        $return .= '<h5 class="mb-0">';
                                            $return .= 'Program Details';
                                        $return .= '</h5>';
                                    $return .= '</div>';
                                    $return .= '<div class="card-block px-0">';
                                        $return .= '<div class="pt-0">';
                                            $return .= '<p>Program Duration</p>';
                                            $return .= '<p><strong>'.get_post_meta($current_product[0]->ID,'program_duration',true).'</strong></p>';
                                        $return .= '</div>';
                                        
                                        $program_distance_learning_duration = get_post_meta($current_product[0]->ID,'program_distance_learning_duration',true);
                                        if($program_distance_learning_duration !== ''){
                                            $return .= '<div>';
                                                $return .= '<p>Distance Learning Duration</p>';
                                                $return .= '<p><strong>'.get_post_meta($current_product[0]->ID,'program_distance_learning_duration',true).'</strong></p>';
                                            $return .= '</div>';
                                        }

                                        $return .= '<div>';
                                            $return .= '<p>Min Participants</p>';
                                            $return .= '<p><strong>'.get_post_meta($current_product[0]->ID,'min_participants',true).'</strong></p>';
                                        $return .= '</div>';
                                        $return .= '<div class="pb-0">';
                                            $return .= '<p>Max Participants</p>';
                                            $return .= '<p><strong>'.get_post_meta($current_product[0]->ID,'max_participants',true).'</strong></p>';
                                        $return .= '</div>';
                                        //$return .= apply_filters('the_content',$course_specs);
                                    $return .= '</div>';
                                $return .= '</div>';
                                $return .= '<div class="card">';
                                    $return .= '<div class="card-header" role="tab">';
                                        $return .= '<h5 class="mb-0 bg-orange">';
                                            $return .= 'Who Should Attend?';
                                        $return .= '</h5>';
                                    $return .= '</div>';
                                    $return .= '<div class="card-block">';
                                        $return .= '<div>';
                                            $return .= apply_filters('the_content',$course_specs);
                                        $return .= '</div>';
                                        //$return .= apply_filters('the_content',$course_specs);
                                    $return .= '</div>';
                                $return .= '</div>';
                                $return .= '<div class="card">';
                                    $return .= '<div class="card-header" role="tab">';
                                        $return .= '<h5 class="mb-0 bg-green">';
                                            $return .= 'Available Locations';
                                        $return .= '</h5>';
                                    $return .= '</div>';
                                    $return .= '<div class="card-block px-0 text-left">';
                                    if($_public_course == 'yes' || $public_product_link) {
                                        $return .= '<div class="pt-0">';
                                            $return .= '<p><img src="wp-content/themes/industrialsafetytrainers/assets/images/classroom-icon.png"/>Public Classroom</p>';
                                        $return .= '</div>';
                                    }
                                    if($_private_course == 'yes') {
                                        $return .= '<div>';
                                            $return .= '<p><img src="wp-content/themes/industrialsafetytrainers/assets/images/on-site-icon.png"/>On-Site</p>';
                                        $return .= '</div>';
                                    }
                                    if($_private_course == 'yes' && $virtual_product_link != '') {
                                        $return .= '<div class="">';
                                            $return .= '<p><img src="wp-content/themes/industrialsafetytrainers/assets/images/safety-bus-icon.png"/>The Safety Bus</p>';
                                        $return .= '</div>';
                                    }
                                    if($_virtual_course == 'yes' || $virtual_product_link != '') {
                                        $return .= '<div class="pb-0">';
                                            $return .= '<p><img style="width:67px" src="wp-content/themes/industrialsafetytrainers/assets/images/online-icon.png"/>Distance Course</p>';
                                        $return .= '</div>';
                                    }
                                    $return .= '</div>';
                                $return .= '</div>';
                            $return .= '</div>';
                        }




                    $return .= '</div>';




                $return .= '</div>';

            }

            $return .= '<div class="category-container">';
            $return .= '<div class="category-background"></div>';
                // COURSE OFFERED
                /*
                $return .= '<div class="course-detail-section">';
                    $return .= '<h5>This course is offered</h5>';
                    $return .= '<div class="courseType">';
                        if($_public_course == 'yes'){ $return .= '<span class="course-type-public"></span>'; }
                        if($_private_course == 'yes'){ $return .= '<span class="course-type-private"></span>'; }
                        if($_online_course == 'yes'){ $return .= '<span class="course-type-online"></span>'; }
                    $return .= '</div>';
                $return .= '</div>';
                */

                // COURSE COST OUTLINE
                $return .= '<div class="course-detail-section price">';
                    $cost_outline = get_post_meta($current_product[0]->ID,'cost_outline',true);

                    if($cost_outline != ''){
                        $return .= '<div>';
                            $return .= '<span>Price*</span>';
                            $return .= apply_filters('the_content',$cost_outline);
                        $return .= '</div>';
                    }
                $return .= '</div>';

                if($_private_course == 'yes' && $current != 4){
                    $return .= '<span class="course-span book-on-site"><button type="button" class="btn btn-primary " role="button" data-toggle="modal" data-target="#requestInformationModal">Book On-Site</button></span>';
                }

                
                if($_public_course == 'yes'){
                    $return .= '<span class="course-span book-classroom"><a href="'.get_site_url($current).'/safety-training-course-public-dates/?course='.$current_product[0]->post_name.'">Classroom Dates</a></span>';
                }elseif($public_product_link != ''){
                    $return .= '<span class="course-span book-classroom"><a href="'.get_site_url(1).'/safety-training-course-public-dates/?course='.$public_product_link.'">Classroom Dates</a></span>';
                }


                if($_online_course == 'yes'){
                    $return .= '<span class="course-span book-on-site"><a class="btn btn-primary" href="'.get_post_meta($current_product[0]->ID,'_product_url',true).'" target="_blank">Book Online</a></span>';
                }

                
                if($_virtual_course == 'yes'){
                    $return .= '<span class="course-span book-on-site mb-4"><a class="btn btn-blue" href="'.get_site_url($current).'/safety-training-course-public-dates/?course='.$current_product[0]->post_name.'" target="_blank">Distance Dates</a></span>';
                }elseif($virtual_product_link != ''){
                    $return .= '<span class="course-span book-on-site mb-4"><a class="btn btn-blue" href="'.get_site_url(1).'/safety-training-course-public-dates/?course='.$virtual_product_link.'" target="_blank">Distance Dates</a></span>';
                }

                // INFO SHEET
                $return .= '<div class="course-detail-section">';
                    $info_sheet = get_post_meta($current_product[0]->ID,'info_sheet',true);
                    if($info_sheet != ''){
                    $return .= '<a href="'.$info_sheet.'" title="'.$current_product[0]->post_title.' Info Sheet" class="pdf-download d-flex align-items-center " target="_blank"><img src="https://thesafetybus.com/wp-content/themes/industrialsafetytrainers/assets/images/pdf-download.png"><span class="pdf-download-download">Info Sheet</span></a>';
                    }
                $return .= '</div>';

                // REGISTRATION
                /*
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
                */

                // WHAT IS INCLUDED
                $return .= '<div class="course-detail-section">';
                    $course_outline = get_post_meta($current_product[0]->ID,'course_outline',true);
                    if($course_outline != ''){
                        $return .= apply_filters('the_content',$course_outline);
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
                        /*
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $parent,
                            )
                        )
                        */
                    );

                    $upsells = get_posts($args);
                    if (!empty($upsells)){
                        $return .= '<p><strong>Do you also need..</strong></p>';
                        $return .= '<ul class="category-list"><li>';
                         $return .= '<ul>';
                        foreach($upsells as $upsell){
                           $return .= '<li><a href="'.$currentBlogUrl.'/'.$slug.'/?course='.$upsell->post_name.'">'.$upsell->post_title.'</a></li>';
                        }
                        $return .= '</ul></li></ul>';
                    }
                }


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
    switch_to_blog(1);



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
                $allLocations = array();
                global $wpdb;

                foreach($variations as $key => $variation){
                    $meta_attribute_pa_location = get_post_meta($variation->ID, 'attribute_pa_location', true);
                    $location_term = $wpdb->get_row('SELECT t.*, tt.*
                            FROM '.$wpdb->terms.' AS t
                            INNER JOIN '.$wpdb->term_taxonomy.' AS tt ON t.term_id = tt.term_id
                            WHERE slug = "'.$meta_attribute_pa_location.'"');
                    array_push($allLocations, $location_term->name);
                }

                $allLocations = array_unique($allLocations);
                sort($allLocations);

                $return .= '<div class="category-background-course-dates"></div>';
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
                        foreach($allLocations as $l) {
                            $location_slug = $wpdb->get_row('SELECT t.*, tt.*
                            FROM '.$wpdb->terms.' AS t
                            INNER JOIN '.$wpdb->term_taxonomy.' AS tt ON t.term_id = tt.term_id
                            WHERE name = "'.$l.'"');
                            $return .= '<li><label><input type="checkbox" name="filter_location" value="'.$location_slug->slug.'" data-parent="'.$current_product[0]->ID.'"/> '.$l.'</label></li>';
                        }
                        $return .= '</ul>';
                    $return .= '</div>';
                    $return .= '<div class="col col-9">';
                        
                        $dates_description =  get_post_meta( $current_product[0]->ID, 'dates_description', true );
                        if($dates_description != ''){
                            $return .= apply_filters('the_content',$dates_description);
                        }

                        $return .= '<table class="table">';
                            $return .= '<thead>';
                                $return .= '<tr>';
                                    $return .= '<th>Location</th>';
                                    $return .= '<th class="sort-date" style="min-width:150px">Date ◆</th>';
                                    $return .= '<th>Time</th>';
                                    if($current === 1){
                                        $return .= '<th>Price</th>';
                                    }
                                    $return .= '<th>Notes</th>';
                                    if($current === 1){
                                        $return .= '<th>Quantity</th>';
                                    }
                                    $return .= '<th>Purchase</th>';
                                $return .= '</tr>';
                            $return .= '</thead>';

                            $return .= '<tbody class="course_list">';
                                foreach ($variations as $key => $variation){
                                     // get variation ID
                                    $variation_ID = $variation->ID;


                                    $product_variation = new WC_Product_Variation( $variation_ID );

                                    $meta_attribute_pa_address = get_post_meta($variation_ID, 'attribute_pa_address', true);
                                    $address_term = $wpdb->get_row('SELECT t.*, tt.*
                                            FROM '.$wpdb->terms.' AS t
                                            INNER JOIN '.$wpdb->term_taxonomy.' AS tt ON t.term_id = tt.term_id
                                            WHERE slug = "'.$meta_attribute_pa_address.'"');

                                    $meta_attribute_pa_time = get_post_meta($variation_ID, 'attribute_pa_time', true);
                                    $time_term = $wpdb->get_row('SELECT t.*, tt.*
                                            FROM '.$wpdb->terms.' AS t
                                            INNER JOIN '.$wpdb->term_taxonomy.' AS tt ON t.term_id = tt.term_id
                                            WHERE slug = "'.$meta_attribute_pa_time.'"');

                                    $meta_attribute_pa_location = get_post_meta($variation_ID, 'attribute_pa_location', true);
                                    $location_term = $wpdb->get_row('SELECT t.*, tt.*
                                            FROM '.$wpdb->terms.' AS t
                                            INNER JOIN '.$wpdb->term_taxonomy.' AS tt ON t.term_id = tt.term_id
                                            WHERE slug = "'.$meta_attribute_pa_location.'"');

                                    //print_r($product_variation);
                                    $variation_price = $product_variation->get_price_html();
                                    $variation_description = $product_variation->get_description();
                                    $variation_stock = $product_variation->get_stock_quantity();

                                    if($variation_stock > 0) {
                                        $return .= '<tr class="course-info-'.strtotime(get_post_meta( $variation_ID, 'attribute_pa_date', true )).'" data-timestamp="'.strtotime(get_post_meta( $variation_ID, 'attribute_pa_date', true )).'">';
                                            $return .= '<td>'.$location_term->name.' - '.$address_term->name.'</td>';
                                            $return .= '<td>'.date('M j, Y',strtotime(get_post_meta( $variation_ID, 'attribute_pa_date', true ))).'</td>';
                                            $return .= '<td>'.$time_term->name.'</td>';
                                            if($current === 1){
                                                $return .= '<td>'.$variation_price.'</td>';
                                            }
                                            $return .= '<td>'.$variation_description.'</td>';
                                            if($current === 1){
                                                $return .= '<td><input type="number" min="0" max="'.$variation_stock.'" placeholder="0" name="course_qty" data-id="'.$variation_ID.'"/></td>';
                                                $return .= '<td><a class="'.$variation_ID.'_url btn btn-primary" href="'.get_bloginfo('url').'/cart/?add-to-cart='.$current_product[0]->ID.'&variation_id='.$variation_ID.'&attribute_pa_address='.$address_term->name.'&attribute_pa_location='.$location_term->name.'&attribute_pa_date='.get_post_meta( $variation_ID, 'attribute_pa_date', true ).'&attribute_pa_time='.$time_term->name.'&quantity=0" target="_blank">Purchase</a></td>';
                                            }else{
                                                switch_to_blog($current);
                                                $return .= '<td><a class="'.$variation_ID.'_url btn btn-primary" href="'.get_bloginfo('url').'/safety-training-course-registration?variation='.$variation_ID.'" target="_blank">Register</a></td>';
                                                switch_to_blog(1);
                                            }
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
    //switch_to_blog($blog_id);


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

    //switch_to_blog($current);

    return $return;

    

}
add_shortcode('course_category_list','course_category_list');
