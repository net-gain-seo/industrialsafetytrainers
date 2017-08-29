<?php 

function course_list($atts){
	extract( shortcode_atts( array(
        'category_id' => ''
    ), $atts ));

	//QUERY POSTS
	wp_reset_query();


    //QUERY
    global $wpdb;

    if(isset($_GET['current_page'])){
        $page = $_GET['current_page'];
    }else{
        $page = 1;
    }

    $per_page = 2;
    $offset = ($page - 1) * $per_page;
    $total = 0;

    $qry = 'SELECT
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
                AND course_date.meta_key = "attribute_pa_date"
            )

            INNER JOIN '.$wpdb->prefix.'postmeta AS course_location ON(
                variation.ID = course_location.post_id
                AND course_location.meta_key = "attribute_pa_location"
            )

            INNER JOIN '.$wpdb->prefix.'postmeta AS course_time ON(
                variation.ID = course_time.post_id
                AND course_time.meta_key = "attribute_pa_time"
            )

            INNER JOIN '.$wpdb->prefix.'posts AS product ON(
                variation.post_parent = product.ID
            )

            WHERE variation.post_type = "product_variation"
            AND variation.post_status = "publish"
            AND product.post_status = "publish"
            AND course_date.meta_value >= NOW()
            ORDER BY course_date ASC
            ';

    //echo $qry;
    $total_courses = $wpdb->get_results($qry);
    $total = count($total_courses);

    $total_pages = $total / $per_page;

    $next_page = $page + 1;
    $prev_page = $page - 1;

    $courses = $wpdb->get_results($qry.' LIMIT '.$per_page.' OFFSET '.$offset.'');

    $return = '';
    $return .= '<div class="course-list">';

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

     $return .= '</div>';

    $return .= '<div class="container d-flex justify-content-center">';
         $current_url = get_permalink();
        if($prev_page > 0){
            $return .= '<div class="nav-next"><a href="'.$current_url.'?current_page='.$prev_page.'"><span class="btn btn-primary left-arrow"></span> Previous Page</a></div>';
        }
        if($next_page <= ceil($total_pages)){
            $return .= '<div class="nav-previous"><a href="'.$current_url.'?current_page='.$next_page.'">Next Page <span class="btn btn-primary right-arrow"></span></a></div>';
        }
    $return .= '</div>';

    return $return;
}

add_shortcode('course_list','course_list');