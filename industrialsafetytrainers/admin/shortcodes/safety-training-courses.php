<?php 


function remove_querystring_var($url, $key) { 
    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
    //print_r($url);
    $url = substr($url, 0, -1); 
    return $url; 
}


function safety_training_courses($atts,$content){
    extract( shortcode_atts( array(
        'blog_id'   => 3,
        'page_slug' => '',
        'category'  => '',
        'public' => false,
        'private' => false,
        'online' => false
    ), $atts ));


    $current = get_current_blog_id();
    $currentBlogUrl = get_bloginfo('url');
    switch_to_blog($blog_id);

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


    $return .= '<div class="container">';
        $return .= '<div class="row">';
            $return .= '<div class="col col-3">';
                $return .= '<form method="get" action="'.get_site_url($current).'/'.$page_slug.'">';
                    $return .= '<ul class="courseCategories">';
                        $category_ids_array = array();
                        foreach ($all_categories as $cat) {
                            $category_ids_array[] = $cat->term_id;
                            $return .= '<li><label><input class="filterCourseByCategory" type="checkbox" name="category_ids[]" value="'. $cat->term_id .'" '.(isset($_GET['category_ids']) && in_array($cat->term_id, $_GET['category_ids'])? "CHECKED" : "").' /> '. $cat->name .'</label></li>';
                        }
                    $return .= '</ul>';

                    $return .= '<input type="submit" name="submit" value="Filter Courses" class="btn btn-primary" />';
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
                    $offset = $postsPerPage * $currentPage;
                }else{
                    $page = 0;
                    $offset = 0;
                    $nextPage = 2;
                    $prevPage = 1;
                }


                $args = array(
                    'posts_per_page'   => $postsPerPage,
                    'offset'           => $offset,
                    'post_type'        => 'product',
                    'post_status'      => 'publish',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => $category_ids_array,
                        )
                    )
                );

                if(isset($_GET['order']) && $_GET['order'] == 'popularity'){
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                }else{
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                }

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

                $posts_array = get_posts( $args );


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

                    $return .= '<a class="'.(((isset($_GET['order']) && $_GET['order'] == 'title') || !isset($_GET['order'])) ? "active":"").'" href="'.$titleUrl.'">Alphabetical</a>';
                    $return .= '<span> | </span>';
                    $return .= '<a class="'.((isset($_GET['order']) && $_GET['order'] == 'popularity') ? "active":"").'" href="'.$popularUrl.'">Most Popular</a>';
                $return .= '</div>'; 

                foreach($posts_array as $product){
                    $return .= '<div class="courseBlock">';
                        $return .= get_the_post_thumbnail($product->ID);
                        $return .= '<div>';
                            $return .= '<h3>'.$product->post_title.'</h3>';
                            $return .= '<p>'.$product->post_excerpt.'</p>';
                            $return .= '<a href="'.get_site_url($current).'/safety-training-course/?course='.$product->post_name.'" class="btn btn-primary">View Details</a>';
                        $return .= '</div>';

                         $return .= '<div class="courseType">';
                            $_public_course = get_post_meta($product->ID,'_public_course',true);
                            $_private_course = get_post_meta($product->ID,'_private_course',true);
                            $_online_course = get_post_meta($product->ID,'_online_course',true);

                            if($_public_course == 'yes'){ $return .= '<span class="course-type-public"></span>'; }
                            if($_private_course == 'yes'){ $return .= '<span class="course-type-private"></span>'; }
                            if($_online_course == 'yes'){ $return .= '<span class="course-type-online"></span>'; }
                        $return .= '</div>';

                        $return .= '<div>';
                            $cost_outline = get_post_meta($product->ID,'cost_outline',true);
                            $return .= apply_filters('the_content',$cost_outline);
                        $return .= '</div>';

                    $return .= '</div>';
                }

                //Pagination 


            $return .= '</div>';
        $return .= '</div>';

    $return .= '</div>';

    $return .= '<div class="container d-flex justify-content-center">';
        if($currentPage > 0){
            $return .= '<div class="nav-next"><a href="'.get_site_url($current).'/'.$page_slug.'/'.$prevPage.'/'.(($_SERVER['QUERY_STRING'] != '' ) ? "?".$_SERVER['QUERY_STRING']:"").'"><span class="btn btn-primary left-arrow"></span> Previous Page</a></div>';
        }
        $return .= '<div class="nav-previous"><a href="'.get_site_url($current).'/'.$page_slug.'/'.$nextPage.'/'.(($_SERVER['QUERY_STRING'] != '' ) ? "?".$_SERVER['QUERY_STRING']:"").'">Next Page <span class="btn btn-primary right-arrow"></span></a></div>';
    $return .= '</div>';


    switch_to_blog($current);


    //Scripts

    return $return;

}
add_shortcode('safety_training_courses','safety_training_courses');