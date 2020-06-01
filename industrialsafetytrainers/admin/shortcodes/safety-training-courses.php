<?php


function remove_querystring_var($url, $key) {
    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
    //print_r($url);
    $url = substr($url, 0, -1);
    return $url;
}


function safety_training_courses($atts,$content){

    $termSlug = '';
    if(isset($_GET['category'])) {
        $termSlug = $_GET['category'];
    }

    extract( shortcode_atts( array(
        'blog_id'   => 1,
        'page_slug' => '',
        'category'  => '',
        'public' => false,
        'private' => false,
        'online' => false
    ), $atts ));

    $current = get_current_blog_id();
    $currentBlogUrl = get_bloginfo('url');
    switch_to_blog(1);

    $return = '';
    $taxonomy     = 'product_cat';

    $args = array(
     'taxonomy'     => $taxonomy,
     'parent'       => $category,
     'orderby'      => 'name',
     'order'        => 'ASC',
    );

    $all_categories = get_terms( $args );

    //Sort by name
    usort($all_categories, function($a, $b){
        return strcmp($a->name, $b->name);
    });

    $return .= '<div class="category-background-course-dates"></div>';
    $return .= '<div class="container">';
        $return .= '<div class="row">';
            $return .= '<div class="col col-3">';
                $return .= '<h2>Pick a Category:</h2>';
                $return .= '<form class="course_filter_form" method="post" action="'.get_site_url($current).'/'.$page_slug.'">';
                //$return .= '<form method="post" action="'.get_site_url($current).'/'.$page_slug.(isset($_GET['order']) ? "/order=".$_GET['order']:"").'">';
                    $return .= '<ul class="courseCategories">';
                        $category_ids_array = array();
                        if(strpos($_SERVER['REQUEST_URI'],'online-courses') !== false) {
                            $urlPart = 'online-courses';
                            $return .= '<li '.(( !isset($_GET['category']) ) ? 'class="active-category"':'').'><a href="'.get_site_url($current).'/online-courses/">All Categories</a></li>';
                        }
                        else if(strpos($_SERVER['REQUEST_URI'],'classroom-courses') !== false) {
                            $urlPart = 'classroom-courses';
                            $return .= '<li '.(( !isset($_GET['category']) ) ? 'class="active-category"':'').'><a href="'.get_site_url($current).'/classroom-courses/">All Categories</a></li>';
                        }
                        else if(strpos($_SERVER['REQUEST_URI'],'on-site-courses') !== false) {
                            $urlPart = 'on-site-courses';
                            $return .= '<li '.(( !isset($_GET['category']) ) ? 'class="active-category"':'').'><a href="'.get_site_url($current).'/on-site-courses/">All Categories</a></li>';
                        }
                        else if(strpos($_SERVER['REQUEST_URI'],'virtual-classroom') !== false) {
                            $urlPart = 'virtual-classroom';
                            $return .= '<li '.(( !isset($_GET['category']) ) ? 'class="active-category"':'').'><a href="'.get_site_url($current).'/virtual-classroom/?category='.$urlPart.'">All Categories</a></li>';
                        }
                        foreach ($all_categories as $cat) {
                            $category_ids_array[] = $cat->term_id;
                            $return .= '<li '.(( isset($_GET['category']) && $cat->slug == $_GET['category']) ? 'class="active-category"':'').'><a href="'.get_site_url($current).'/'.$urlPart.'/?category='.$cat->slug.'#coursesOffered">'.$cat->name.'</a></li>';

                            if(isset($_GET['category']) && $_GET['category'] == $cat->slug) {
                                $catName = $cat->name;
                            }
                            else if(!isset($_GET['category'])) {
                                $catName = 'All Categories';
                            }
                        }
                    $return .= '</ul>';
                $return .= '</form>';
            $return .= '</div>';
            $return .= '<div class="col col-9">';

                 //Set up current page variable and offset.
                $postsPerPage = 6;
                $page = get_query_var( 'page', 1 );
                if($page > 0){
                    $currentPage = $page;
                    $nextPage = $page + 1;
                    $prevPage = $page - 1;
                    $offset = $postsPerPage * ($currentPage - 1);
                }else{
                    $page = 0;
                    $offset = 0;
                    $nextPage = 2;
                    $prevPage = 1;
                }

                if($termSlug == '') {
                    $args = array(
                        'posts_per_page'   => $postsPerPage,
                        'offset'           => $offset,
                        'post_type'        => 'product',
                        'post_status'      => 'publish'
                    );
                }
                else {
                    $args = array(
                        'posts_per_page'   => $postsPerPage,
                        'offset'           => $offset,
                        'post_type'        => 'product',
                        'post_status'      => 'publish',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'slug',
                                'terms' => $termSlug,
                            )
                        )
                    );
                }

                if(isset($_GET['order']) && $_GET['order'] == 'title'){
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                }else{
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                }

                /*
                *
                * SPECIAL NOTE: This needs to be uncommented 
                *
                *
                if($public || $private || $online){
                    $args['meta_query'] = array();
                    if($public){
                        $args['meta_query'][] = array(
                            'key' => '_public_course',
                            'value' => 'yes'
                        );
                    }
                    if($private){
                        $args['meta_query'][] = array(
                            'key' => '_private_course',
                            'value' => 'yes'
                        );
                    }
                    if($online){
                        $args['meta_query'][] = array(
                            'key' => '_online_course',
                            'value' => 'yes'
                        );
                    }
                }
                */

                $posts_array = get_posts( $args );

                $return .= '<h2>Courses Offered - '.$catName.'</h2>';

                if(isset($_GET['category']) && $_GET['category'] == 'virtual-classroom'){
                    $return .= '<p>As MLTSD Approved Training Providers, we have developed a new way of learning called Distance Learning.<br/>Click <a href="https://thesafetybus.com/distance-learning">Here</a> for details</p>';
                }

                //$return .= '<p>Not all courses are offered publicly. To view all courses we offer <a href="'.get_site_url($current).'/classroom-courses">click here!</a></p>';
                //Sorting
                $return .= '<div class="courseSort">';

                    //Remove order from query string
                    $string = '?'.$_SERVER['QUERY_STRING'];
                    $parts = parse_url($string);
                    $queryParams = array();
                    parse_str($parts['query'], $queryParams);
                    unset($queryParams['order']);

                    $queryString = http_build_query($queryParams);
                    $url = $parts['path'] . '?' . $queryString;

                    $titleUrl = get_site_url($current).'/'.$page_slug.'/?order=title'.(($queryString != '' ) ? "&".$queryString:"");
                    $popularUrl = get_site_url($current).'/'.$page_slug.'/?order=popularity'.(($queryString != '' ) ? "&".$queryString:"");

                    $return .= '<a class="'.((isset($_GET['order']) && $_GET['order'] == 'popularity') ? "active":"").'" href="'.$popularUrl.'">Most Popular</a>';
                    $return .= '<span> | </span>';
                    $return .= '<a class="'.(((isset($_GET['order']) && $_GET['order'] == 'title') || !isset($_GET['order'])) ? "active":"").'" href="'.$titleUrl.'">Alphabetical</a>';
                $return .= '</div>';

                foreach($posts_array as $product){
                    $_public_course = get_post_meta($product->ID,'_public_course',true);
                    $_private_course = get_post_meta($product->ID,'_private_course',true);
                    $_online_course = get_post_meta($product->ID,'_online_course',true);
                    $_virtual_course = get_post_meta($product->ID,'_virtual_course',true);

                    $virtual_product_link = get_post_meta($product->ID,'virtual_product_link',true);
                    $public_product_link = get_post_meta($product->ID,'public_product_link',true);

                    $return .= '<div class="courseBlock">';
                        if(has_post_thumbnail($product->ID)) {
                            $return .= get_the_post_thumbnail($product->ID);
                        }
                        $return .= '<div>';
                            $return .= '<h3>'.$product->post_title.'</h3>';

                            $product_sub_heading =  get_post_meta( $product->ID, 'product_sub_heading', true );
                            if($product_sub_heading != ''){
                                $return .= '<h5>'.$product_sub_heading.'</h5>';    
                            }

                            $return .= '<p>'.strip_tags(substr($product->post_excerpt,0,150)).'... <a href="'.get_site_url(1).'/safety-training-course/?course='.$product->post_name.'">Read More>></a></p>';
                            
                            $return .= '<a href="'.get_site_url(1).'/safety-training-course/?course='.$product->post_name.'" class="btn btn-primary">View Details</a>';
                            
                            
                            if($_public_course == 'yes'){ 
                                $return .= '<a href="'.get_site_url(1).'/safety-training-course-public-dates/?course='.$product->post_name.'" class="btn btn-green ml-2">View Public Dates</a>'; 
                            }

                            if($_virtual_course == 'yes'){ 
                                $return .= '<a href="'.get_site_url(1).'/safety-training-course-public-dates/?course='.$product->post_name.'" class="btn btn-blue ml-2">View Distance Dates</a>'; 
                            }

                            if($virtual_product_link != ''){
                                $return .= '<a href="'.get_site_url(1).'/safety-training-course/?course='.$virtual_product_link.'" class="btn btn-blue ml-2">Distance Details</a>';
                            }

                            if($public_product_link != ''){
                                $return .= '<a href="'.get_site_url(1).'/safety-training-course/?course='.$public_product_link.'" class="btn btn-green ml-2">Public Details</a>';
                            }
                       
                            $return .= '</div>';

                        $return .= '<div class="courseType">';
                            if($_public_course == 'yes' || $public_product_link != ''){ $return .= '<span class="d-block mb-0 py-2"><img src="'.get_site_url($current).'/wp-content/themes/industrialsafetytrainers/assets/images/check-mark.png'.'" width="20" height="21"/> Classroom</span>'; }
                            if($_private_course == 'yes'){ $return .= '<span class="d-block mb-0 py-2"><img src="'.get_site_url($current).'/wp-content/themes/industrialsafetytrainers/assets/images/check-mark.png'.'" width="20" height="21"/> On-Site</span>'; }
                            if($_online_course == 'yes'){ $return .= '<span class="d-block mb-0 py-2"><img src="'.get_site_url($current).'/wp-content/themes/industrialsafetytrainers/assets/images/check-mark.png'.'" width="20" height="21"/> Online</span>'; }
                            if($_virtual_course == 'yes' || $virtual_product_link != ''){ $return .= '<span class="d-block mb-0 py-2"><img src="'.get_site_url($current).'/wp-content/themes/industrialsafetytrainers/assets/images/check-mark.png'.'" width="20" height="21"/> Distance</span>'; }
                        $return .= '</div>';

                        $return .= '<div class="coursePrice">';
                            $cost_outline = get_post_meta($product->ID,'cost_outline',true);
                            $return .= apply_filters('the_content',$cost_outline);
                        $return .= '</div>';
                    $return .= '</div>';
                    $return .= '<hr/>';
                }

                //Pagination


            $return .= '</div>';
        $return .= '</div>';

    $return .= '</div>';

    $return .= '<div class="container d-flex justify-content-center">';
        if($currentPage > 0){
            $return .= '<div class="nav-next"><a href="'.get_site_url($current).'/'.$page_slug.'/'.$prevPage.'/'.(($_SERVER['QUERY_STRING'] != '' ) ? "?".$_SERVER['QUERY_STRING']:"").'"><span class="btn btn-primary left-arrow"></span> Previous Page</a></div>';
        }
        if(sizeof($posts_array) > 5) {
            $return .= '<div class="nav-previous"><a href="'.get_site_url($current).'/'.$page_slug.'/'.$nextPage.'/'.(($_SERVER['QUERY_STRING'] != '' ) ? "?".$_SERVER['QUERY_STRING']:"").'">Next Page <span class="btn btn-primary right-arrow"></span></a></div>';
        }
    $return .= '</div>';


    switch_to_blog($current);


    //Scripts

    return $return;

}
add_shortcode('safety_training_courses','safety_training_courses');