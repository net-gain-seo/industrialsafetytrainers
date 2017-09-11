<?php 

function course_list_query($query_category_id,$includeLimit = false,$per_page=10,$offset=0,$location = false,$month = false, $search = ''){
    global $wpdb;

    $course_query = 'SELECT
        product.ID                                          AS product_id,
        product.post_content                                AS post_content,
        product.post_title                                  AS post_title,
        course_date.post_id                                 AS variation_id,
        course_date.meta_value                              AS course_date,
        course_location.meta_value                          AS course_location,
        course_time.meta_value                              AS course_time

        FROM '.$wpdb->prefix.'posts AS variation

        INNER JOIN '.$wpdb->prefix.'postmeta AS course_date ON(
            variation.ID = course_date.post_id
            AND course_date.meta_key = "attribute_pa_date"';

            if($month){
                $course_query .= ' AND course_date.meta_value LIKE "%-'.$month.'-%" ';
            }

        $course_query .= '
        )

        INNER JOIN '.$wpdb->prefix.'postmeta AS course_location ON(
            variation.ID = course_location.post_id
            AND course_location.meta_key = "attribute_pa_location"';
            if($location){
                $course_query .= ' AND course_location.meta_value = "'.$location.'" ';
            }
        $course_query .= '
        )

        INNER JOIN '.$wpdb->prefix.'postmeta AS course_time ON(
            variation.ID = course_time.post_id
            AND course_time.meta_key = "attribute_pa_time"
        )

        INNER JOIN '.$wpdb->prefix.'posts AS product ON(
            variation.post_parent = product.ID
        )

        INNER JOIN wp_3_term_relationships ON(
            wp_3_term_relationships.term_taxonomy_id = '.$query_category_id.'
            AND wp_3_term_relationships.object_id = product.ID
        )

        WHERE variation.post_type = "product_variation"
        AND variation.post_status = "publish"
        AND product.post_status = "publish"
        AND course_date.meta_value >= NOW()';

        if($search != ''){
            $course_query .= ' AND product.post_title LIKE "%'.$search.'%"';
        }

        $course_query .= ' ORDER BY course_date ASC';

        if($includeLimit){
            $course_query .= ' LIMIT '.$per_page.' OFFSET '.$offset.'';
        }

    $results = $wpdb->get_results($course_query);
    return $results;
}



function course_list($atts){
	extract( shortcode_atts( array(
        'category_id'       => '',
        'include_filters'   => 'true',
        'container'         => 'container',
        'search'            => ''
    ), $atts ));

    $query_category_id = $category_id;
    if(isset($_GET['category']) && $_GET['category'] != ''){
        $query_category_id = $_GET['category'];
    }

    $query_location = false;
    if(isset($_GET['location']) && $_GET['location'] != ''){
        $query_location = $_GET['location'];
    }

    $query_month = false;
    if(isset($_GET['month']) && $_GET['month'] != ''){
        $query_month = $_GET['month'];
    }
    
    $current_url = get_permalink();

    if(isset($_GET['current_page'])){
        $page = $_GET['current_page'];
    }else{
        $page = 1;
    }

    $per_page = 10;
    $offset = ($page - 1) * $per_page;
    $total = 0;

    
    //Queries 
    $total_courses = course_list_query($query_category_id,false,$per_page,$offset,$query_location,$query_month, $search);
    $courses = course_list_query($query_category_id,true,$per_page,$offset,$query_location,$query_month, $search);
    $locations = course_list_query($query_category_id,false,$per_page,$offset,false,false, $search);

    $total = count($total_courses);
    $total_pages = $total / $per_page;

    $next_page = $page + 1;
    $prev_page = $page - 1;

    //Get location
    $locationsArray = array();
    foreach($locations as $course){
        if(!array_key_exists($course->course_location, $locationsArray)){
            $location_term = get_term_by( 'slug', $course->course_location, 'pa_location' );
            $location_name = $location_term->name;
            $locationsArray[$course->course_location] = array('value'=> $course->course_location,'formated'=>$location_name);
        }
    }

    //Get Categories
    $categories = get_categories( array ('taxonomy' => 'product_cat', 'parent' => $category_id ));

    $return = '';
    if($include_filters == 'true'){
        $return .= '<div class="container-fluid" style="background-color: #FFD500;">';
            $return .= '<div class="container">';
                $return .= '<form class="search_courses_form" method="get" action="'.$current_url.'">';
                    $return .= '<select name="month">';
                        $return .= '<option value="0" '.((isset($_GET['month']) && $_GET['month'] == '0')?"SELECTED":"").'>Choose Month</option>';
                        $return .= '<option value="01" '.((isset($_GET['month']) && $_GET['month'] == '01')?"SELECTED":"").'>January</option>';
                        $return .= '<option value="02" '.((isset($_GET['month']) && $_GET['month'] == '02')?"SELECTED":"").'>February</option>';
                        $return .= '<option value="03" '.((isset($_GET['month']) && $_GET['month'] == '03')?"SELECTED":"").'>March</option>';
                        $return .= '<option value="04" '.((isset($_GET['month']) && $_GET['month'] == '04')?"SELECTED":"").'>April</option>';
                        $return .= '<option value="05" '.((isset($_GET['month']) && $_GET['month'] == '05')?"SELECTED":"").'>May</option>';
                        $return .= '<option value="06" '.((isset($_GET['month']) && $_GET['month'] == '06')?"SELECTED":"").'>June</option>';
                        $return .= '<option value="07" '.((isset($_GET['month']) && $_GET['month'] == '07')?"SELECTED":"").'>July</option>';
                        $return .= '<option value="08" '.((isset($_GET['month']) && $_GET['month'] == '08')?"SELECTED":"").'>August</option>';
                        $return .= '<option value="09" '.((isset($_GET['month']) && $_GET['month'] == '09')?"SELECTED":"").'>September</option>';
                        $return .= '<option value="10" '.((isset($_GET['month']) && $_GET['month'] == '10')?"SELECTED":"").'>October</option>';
                        $return .= '<option value="11" '.((isset($_GET['month']) && $_GET['month'] == '11')?"SELECTED":"").'>November</option>';
                        $return .= '<option value="12" '.((isset($_GET['month']) && $_GET['month'] == '12')?"SELECTED":"").'>December</option>';
                    $return .= '</select>';
                    $return .= '<select name="category">';
                        $return .= '<option value="">Choose Category</option>';
                        foreach($categories as $cat){
                            $return .= '<option value="'.$cat->term_id.'" '.(($query_category_id != '' && $query_category_id == $cat->term_id)?"SELECTED":"").'>'.$cat->name.'</option>';
                        }
                    $return .= '</select>';
                    $return .= '<select name="location">';
                        $return .= '<option value="">Choose Location</option>';
                        foreach($locationsArray as $loc){
                            $return .= '<option value="'.$loc['value'].'" '.((isset($_GET['location']) && $_GET['location'] == $loc['value'])?"SELECTED":"").'>'.$loc['formated'].'</option>';
                        }
                    $return .= '</select>';
                    $return .= '<input type="submit" value="SEARCH" name="search_courses" class="btn btn-warning" />';
                $return .= '</form>';
            $return .= '</div>';
        $return .= '</div>';
    }

    $return .= '<div class="'.$container.'">';
        $return .= '<div class="course-list">';
            if(count($courses) == 0){
                $return .= '<p>Sorry, No courses match your criteria. Please check back later.</p>';
            }else{
                foreach($courses as $course){
                    $return .= '<div class="course">';
                        $return .= '<div class="course-header">';
                            $return .= '<div class="course-date">';
                                $return .= '<span>'.date('M',strtotime($course->course_date)).'</span>';
                                $return .= '<span>'.date('d',strtotime($course->course_date)).'</span>';
                            $return .= '</div>';

                            $return .= '<div class="course-title">';
                                $return .= '<h2>'.$course->post_title.'</h2>';
                                $return .= '<span>'.$course->course_time.'</span>';
                                $return .= '<span>'.$course->course_location.'</span>';
                            $return .= '</div>';
                        $return .= '</div>';

                        $return .= '<div class="course-content">';
                            $return .= apply_filters('the_content',$course->post_content);
                            $return .= '<a class="btn btn-danger" href="'.get_permalink($course->product_id).'">More Information</a>';
                        $return .= '</div>';
                    $return .= '</div>';
                }
            }
         $return .= '</div>';

        $return .= '<div class="container d-flex justify-content-center">';
            if($prev_page > 0){
                $return .= '<div class="nav-next"><a href="'.$current_url.'?current_page='.$prev_page.'"><span class="btn btn-primary left-arrow"></span> Previous Page</a></div>';
            }
            if($next_page <= ceil($total_pages)){
                $return .= '<div class="nav-previous"><a href="'.$current_url.'?current_page='.$next_page.'">Next Page <span class="btn btn-primary right-arrow"></span></a></div>';
            }
        $return .= '</div>';

    $return .= '</div>';

    return $return;
}

add_shortcode('course_list','course_list');