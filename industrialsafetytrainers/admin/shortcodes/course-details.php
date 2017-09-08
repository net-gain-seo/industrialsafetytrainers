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

    $args = array(
         'hide_empty'       => 0,
         'orderby'          => $orderby,
         'parent'           => $parent
    );
    $terms = get_terms('product_cat',$args);

    $return .= '<div class="container-fluid">';
        $return .= '<div class="container course-category-details">';
            $return .= '<div class="category-container">';
								$return .= '<div class="category-background"></div>';
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
                                    break;
                                }
                            }

                            $return .= '<li>';
                                 $return .= '<a data-toggle="collapse" data-target="#product-category-'.$term->slug.'" href="#" class="'.((!$isActive)?"collapsed":"").'">+ '.$term->name.'</a>';
                                if(!empty($products)){
                                    $return .= '<ul id="product-category-'.$term->slug.'" class="'.((!$isActive)?"collapse":"").'">';
                                    foreach($products as $product){
                                        $return .= '<li><a class="'.((isset($_GET['course']) && $_GET['course'] == $product->post_name)?"active":"").'" href="'.$currentBlogUrl.'/'.$slug.'/?course='.$product->post_name.'">'.$product->post_title.'</a></li>';
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

                        $return .= '<li>';
                             //$return .= '<a data-toggle="collapse" data-target="#product-category-'.$term->slug.'" href="#" class="'.((!$isActive)?"collapsed":"").'">+ '.$term->name.'</a>';
                            if(!empty($products)){
                                $return .= '<ul id="product-category-'.$term->slug.'" class="'.((!$isActive)?"collapse":"").'">';
                                foreach($products as $product){
                                    $return .= '<li><a class="'.((isset($_GET['course']) && $_GET['course'] == $product->post_name)?"active":"").'" href="'.$currentBlogUrl.'/'.$slug.'/?course='.$product->post_name.'#course-container">'.$product->post_title.'</a></li>';
                                }
                                $return .= '</ul>';
                            }
                        $return .= '</li>';
                    }
                }
                $return .= '</ul>';
            $return .= '</div>';


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
                    $return .= '<div class="course-header">';
                         $return .= '<div class="course-header-bg"></div>';

                        $return .= '<div class="course-header-details">';
                            $return .= '<h3>COURSE SUMMARY</h3>';
                            $return .= '<div class="course-type-container">';
                                $_public_course = get_post_meta($current_product[0]->ID,'_public_course',true);
                                $_private_course = get_post_meta($current_product[0]->ID,'_private_course',true);
                                $_online_course = get_post_meta($current_product[0]->ID,'_online_course',true);

                                if($_public_course == 'yes'){ $return .= '<span class="course-type-public"></span>'; }
                                if($_private_course == 'yes'){ $return .= '<span class="course-type-private"></span>'; }
                                if($_online_course == 'yes'){ $return .= '<span class="course-type-online"></span>'; }

                            $return .= '</div>';
                        $return .= '</div>';
                    $return .= '</div>';

                    $return .= '<div class="course-detail">';
                        $return .= apply_filters('the_content',$current_product[0]->post_content);
                    $return .= '</div>';


                    $return .= '<div class="accordion" role="tablist" aria-multiselectable="true">';


                        // COURSE SPECS
                        $course_specs = get_post_meta($current_product[0]->ID,'course_specs',true);
                        if($course_specs != ''){
                            $return .= '<div class="card">';
                                $return .= '<div class="card-header" role="tab">';
                                    $return .= '<h5 class="mb-0">';
                                        $return .= '<a data-toggle="collapse" data-parent="#accordion" href="#courseSpecs" aria-expanded="true" aria-controls="courseSpecs">Course Specs</a>';
                                    $return .= '</h5>';
                                $return .= '</div>';

                                $return .= '<div id="courseSpecs" class="accordion-card-contents collapse show" role="tabpanel">';
                                    $return .= '<div class="card-block">';

                                        $return .= apply_filters('the_content',$course_specs);
                                    $return .= '</div>';
                                $return .= '</div>';
                            $return .= '</div>';
                        }


                        // COURSE OUTLINE
                        $course_outline = get_post_meta($current_product[0]->ID,'course_outline',true);

                        if($course_outline != ''){
                            $return .= '<div class="card">';
                                $return .= '<div class="card-header" role="tab">';
                                    $return .= '<h5 class="mb-0">';
                                        $return .= '<a data-toggle="collapse" data-parent="#accordion" href="#courseOutline" aria-expanded="false" aria-controls="courseOutline" class="collapsed">Course Outline</a>';
                                    $return .= '</h5>';
                                $return .= '</div>';

                                $return .= '<div id="courseOutline" class="accordion-card-contents collapse" role="tabpanel">';
                                    $return .= '<div class="card-block">';
                                        $return .= apply_filters('the_content',$course_outline);
                                    $return .= '</div>';
                                $return .= '</div>';
                            $return .= '</div>';
                        }


                        // COURSE COST OUTLINE
                        $cost_outline = get_post_meta($current_product[0]->ID,'cost_outline',true);

                        if($cost_outline != ''){
                            $return .= '<div class="card">';
                                $return .= '<div class="card-header" role="tab">';
                                    $return .= '<h5 class="mb-0">';
                                        $return .= '<a data-toggle="collapse" data-parent="#accordion" href="#courseCostOutline" aria-expanded="false" aria-controls="courseCostOutline" class="collapsed">Cost Outline</a>';
                                    $return .= '</h5>';
                                $return .= '</div>';

                                $return .= '<div id="courseCostOutline" class="accordion-card-contents collapse" role="tabpanel">';
                                    $return .= '<div class="card-block">';
                                        $return .= apply_filters('the_content',$cost_outline);
                                    $return .= '</div>';
                                $return .= '</div>';
                            $return .= '</div>';
                        }

                    $return .= '</div>';


                    $return .= '<div class="course-register-links">';
                        $_product = wc_get_product( $current_product[0]->ID );

                        if($_public_course == 'yes'){
                            $return .= '<div><a href="'.$current_product[0]->guid.'" class="btn btn-danger">REGISTER NOW</a></div>';
                        }

                        if($_private_course == 'yes'){
                            $return .= '<div><a href="'.$current_product[0]->guid.'" class="btn btn-warning">REQUEST INFORMATION</a></div>';
                        }


                        $demo_url = get_post_meta($current_product[0]->ID,'demo_url',true);
                        if($demo_url != ''){
                            $return .= '<div><a href="'.$demo_url.'" target="_blank" class="btn btn-warning">DEMO REQUEST</a></div>';
                        }

                        if($_online_course == 'yes'){
                            if( $_product->is_type( 'external' ) ){
                                $_product_url = get_post_meta($current_product[0]->ID,'_product_url',true);
                                
                                $return .= '<div><a href="'.$_product_url.'" class="btn btn-danger" target="_blank">PURCHASE COURSE</a></div>';
                            }else{
                                $return .= '<div><a href="'.$current_product[0]->guid.'" class="btn btn-danger">PURCHASE COURSE</a></div>';
                            }
                            
                        }

                        $info_sheet = get_post_meta($current_product[0]->ID,'info_sheet',true);
                        if($info_sheet != ''){
                        $return .= '<a href="'.$info_sheet.'" title="'.$current_product[0]->post_title.' Info Sheet" class="pdf-download d-flex align-items-center " target="_blank"><img src="http://209.126.119.193/~industrialsafety/wp-content/themes/industrialsafetytrainers/assets/images/pdf-download.png"><span class="pdf-download-download">Download Info Sheet</span></a>';
                        }
                    $return .= '</div>';

                $return .= '</div>';

            }


        $return .= '</div>';
    $return .= '</div>';



    switch_to_blog($current);
    return $return;
}

add_shortcode('course_details','course_details');
