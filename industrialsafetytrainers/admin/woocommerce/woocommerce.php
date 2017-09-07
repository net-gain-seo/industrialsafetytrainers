<?php 
add_filter( 'woocommerce_product_subcategories_hide_empty', 'hide_empty_categories', 10, 1 );
function hide_empty_categories ( $hide_empty ) {
    $hide_empty  =  FALSE;
    // You can add other logic here too
    return $hide_empty;
}






/**
 * Show pricing fields for course product.
 */
function course_custom_js() {
	if ( 'product' != get_post_type() ) :
		return;
	endif;
	?>
		<script type='text/javascript'>
			jQuery( document ).ready( function() {
				//jQuery('.options_group.pricing').addClass( 'show_if_course' ).show();
				//jQuery('.general_options.general_tab').addClass( 'show_if_course' ).show();
				//jQuery('.attribute_options.attribute_tab').addClass( 'show_if_course' ).show();
				//jQuery('.variations_options.variations_tab').addClass( 'show_if_course' ).show();


				//DATE PICKER
				jQuery(document).ready(function(){
					jQuery('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });	
				});


				//Add course Location				

				jQuery('#add-course-location').click(function(){
					var courseLocationCount = jQuery('.course-location').length;
					var nextCount = courseLocationCount + 1;

					var html = '';

					html += '<div style="margin-bottom: 50px;" class="course-location">';
						html += '<p style="text-align: right;">';
							html += '<input type="text" style="float:left" name="_locations[location-'+nextCount+']" value="location-'+nextCount+'" /> <button data-key="location-'+nextCount+'" class="add-course-date button button-primary">Add Date</button> - <button class="remove-course-location button button-primary">Remove Location</button>';
						html += '</p>';
						html += '<div>';
							html += '<table class="widefat fixed">';
								html += '<thead>';
									html += '<tr>';
										html += '<th>ID</th>';
										html += '<th>Date</th>';
										html += '<th>Time</th>';
										html += '<th>Maximum</th>';
										html += '<th>Cost</th>';
									html += '</tr>';
								html += '</thead>';
								html += '<tbody class="tbody-location-'+nextCount+'">';

								html += '</tbody>';
							html += '</table>';
						html += '</div>';
					html += '</div>';

					jQuery('#course-location-dates').append(html);


					return false;
				});

				jQuery(document).on('click','.add-course-date',function(){
					var key = jQuery(this).attr('data-key');

					var html = '';

					html += '<tr>';
						html += '<td>NA';
							html += '<input name="_location_'+key+'_variation_id[]" value="" readonly="true" type="hidden" />';
							html += '<input name="_location_'+key+'_location[]" value="" readonly="true" type="hidden" />';
						html += '</td>';
						html += '<td><input class="datepicker" type="text" name="_location_'+key+'_dates[]" value="" style="width: 100%" /></td>';
						html += '<td><input type="text" name="_location_'+key+'_dates_time[]" value="" style="width: 100%" /></td>';
						html += '<td><input type="text" name="_location_'+key+'_dates_max[]" value="" style="width: 100%" /></td>';
						html += '<td><input type="text" name="_location_'+key+'_dates_cost[]" value="" style="width: 100%" /></td>';
						html += '<td><button style="float:right" class="remove-course-date button button-primary">Remove Date</button></td>';
					html += '</tr>';

					jQuery('.tbody-'+key).append(html);

					//re up datepicker
					jQuery(document).ready(function(){
						jQuery('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });	
					});

					return false;
				});

				jQuery(document).on('click','.remove-course-date',function(){
					jQuery(this).parent('td').parent('tr').remove();

					return false;
				});

				jQuery(document).on('click','.remove-course-location',function(){
					jQuery(this).parent('p').parent('div').remove();

					return false;
				});

			});
		</script>
	<?php
}
add_action( 'admin_footer', 'course_custom_js' );


/**
 * Add a custom product tab.
 */
function custom_product_tabs( $tabs) {
	$tabs['course'] = array(
		'label'		=> __( 'Course Availability', 'woocommerce' ),
		'target'	=> 'course_options',
		'class'		=> array( 'show_if_variable', 'show_if_variable_course'  ),
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'custom_product_tabs' );




function get_variations($proudct_id){
	global $woocommerce, $product, $post;

	$product_variations = new WC_Product_Variable($proudct_id);

	//echo '<pre>';
	//print_r($product_variations);
	//echo '</pre>';

	$variables = $product_variations->get_available_variations();
	//echo '<pre>';
	//print_r($variables);
	//echo '</pre>';

	//build our variation array for easier display
	$var_out = array();
	foreach($variables as $key => $var){

		$loc_key = $var['attributes']['attribute_pa_location'];
		// SET location key to array if not already;

		$meta_attribute_pa_location = get_post_meta( $var['variation_id'], 'attribute_pa_location', true );
	    $location_term = get_term_by( 'slug', $meta_attribute_pa_location, 'pa_location' );
	    $location_name = $location_term->name;

	    $meta_attribute_pa_date = get_post_meta( $var['variation_id'], 'attribute_pa_date', true );
	    $date_term = get_term_by( 'slug', $meta_attribute_pa_date, 'pa_date' );
	    $date_name = $date_term->name;

	    $meta_attribute_pa_time = get_post_meta( $var['variation_id'], 'attribute_pa_time', true );
	    $time_term = get_term_by( 'slug', $meta_attribute_pa_time, 'pa_time' );
	    $time_name = $time_term->name;

		if(!isset($var_out[$loc_key])){
			$var_out[$loc_key] = array();
			$var_out[$loc_key]['location'] = $location_name;
			$var_out[$loc_key]['items'] = array();
		}


		//Store values in location key array
		$var_out[$loc_key]['items'][] = array(
			'id' 			=> $var['variation_id'],
			'max_qty'		=> $var['max_qty'],
			'display_price'	=> $var['display_price'],
			'location' 		=> $location_name,
			'date' 			=> $date_name,
			'time' 			=> $time_name
		);
	}

	return $var_out;
}

/**
 * Contents of the course options product tab.
 */
function course_options_product_tab_content() {
	global $woocommerce, $product, $post;
	?>
	<div id='course_options' class='panel woocommerce_options_panel'>
		<div class='options_group'>

			<?php $var_out = get_variations($post->ID); ?>

			<div style="padding: 20px;">
				<button class="button button-primary" id="add-course-location">Add Location</button>
			</div>


			<div>
				<div id="course-location-dates" style="padding:20px;">
					<?php foreach($var_out as $key => $var){ ?>
						<div style="margin-bottom: 50px;" class="course-location">
							<p style="text-align: right;">
								<input type="text" name="_locations[<?php echo $key; ?>]" value="<?php echo $var_out[$key]['location']; ?>" style="float:left" /> 
								<button data-key="<?php echo $key; ?>" class="add-course-date button button-primary">Add Date</button> - 
								<button class="remove-course-location button button-primary">Remove Location</button>
							</p>
							<div>
								<table class="widefat fixed">
									<thead>
										<tr>
											<th>ID</th>
											<th>Date</th>
											<th>Time</th>
											<th>Maximum</th>
											<th>Cost</th>
											<th></th>
										</tr>
									</thead>
									<tbody class="tbody-<?php echo $key; ?>">
										<?php foreach($var['items'] as $date){ ?>
										<tr>
											<td><?php echo $date['id']; ?>
												<input name="_location_<?php echo $key; ?>_variation_id[]" value="<?php echo $date['id']; ?>" readonly="true" type="hidden" />
												<input name="_location_<?php echo $key; ?>_location[]" value="<?php echo $date['location']; ?>" readonly="true" type="hidden" />
											</td>
											<td><input type="text" class="datepicker" name="_location_<?php echo $key; ?>_dates[]" value="<?php echo $date['date']; ?>" style="width: 100%" /></td>
											<td><input type="text" name="_location_<?php echo $key; ?>_dates_time[]" value="<?php echo $date['time']; ?>" style="width: 100%" /></td>
											<td><input type="text" name="_location_<?php echo $key; ?>_dates_max[]" value="<?php echo $date['max_qty']; ?>" style="width: 100%" /></td>
											<td><input type="text" name="_location_<?php echo $key; ?>_dates_cost[]" value="<?php echo $date['display_price']; ?>" style="width: 100%" /></td>
											<td><button style="float:right" class="remove-course-date button button-primary">Remove Date</button></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php

}
add_action( 'woocommerce_product_data_panels', 'course_options_product_tab_content' );





/**
 * Save the custom fields.
 */
function save_course_option_field( $product_id ) {

	$is_course = isset( $_POST['_course'] ) ? 'yes' : 'no';
	update_post_meta( $product_id, '_course', $is_course );

	$is_online_course = isset( $_POST['_online_course'] ) ? 'yes' : 'no';
	update_post_meta( $product_id, '_online_course', $is_online_course );

	$is_public_course = isset( $_POST['_public_course'] ) ? 'yes' : 'no';
	update_post_meta( $product_id, '_public_course', $is_public_course );

	$is_private_course = isset( $_POST['_private_course'] ) ? 'yes' : 'no';
	update_post_meta( $product_id, '_private_course', $is_private_course );


	if(isset($_POST['_locations'])){

		
		//CONFIGURE ATTRIBUTES
		$location = wc_attribute_taxonomy_name('Location');
		$date = wc_attribute_taxonomy_name('Date');
		$time = wc_attribute_taxonomy_name('Time');

		$attributes = array(
			$time => array(
				'name' => $time,
				'value' => '',
				'is_visible' => '1',
				'is_variation' => '1',
				'is_taxonomy' => '1'
			),
			$date => array(
				'name' => $date,
				'value' => '',
				'is_visible' => '1',
				'is_variation' => '1',
				'is_taxonomy' => '1'
			),
			$location => array(
				'name' => $location,
				'value' =>'',
				'is_visible' => '1',
				'is_variation' => '1',
				'is_taxonomy' => '1'
			)
		);


		//SET PRODUCT ATTRIBUTES
		update_post_meta( $product_id, '_product_attributes', $attributes );



		// Assign location and dates to the main product
		$locationsArray = array();
		$datesArray = array();
		$timesArray = array();

		foreach($_POST['_locations'] as $l_key => $l_val){
			$locationsArray[] = $l_val;
			
			if(isset($_POST['_location_'.$l_key.'_dates'])){
				foreach($_POST['_location_'.$l_key.'_dates'] as $d_key => $d_val){
					$datesArray[] = $d_val;
					$timesArray[] = $_POST['_location_'.$l_key.'_dates_time'][$d_key];
				}
			}
		}
		wp_set_object_terms($product_id, $locationsArray, $location );
		wp_set_object_terms($product_id, $datesArray, $date );
		wp_set_object_terms($product_id, $timesArray, $time );


		
		$parent_id = $product_id;

		// GET CURRENT VARIATIONS AND MAKE SURE WE DONT RE-ADD. 
		$current_variations = get_variations($product_id);

		echo '<pre>';
		print_r($current_variations);
		echo '</pre>';
		

		$current_variation_ids = array();
		foreach($current_variations as $key => $variations){
			foreach($variations['items'] as $var){
				//foreach($var as $v){
					$current_variation_ids[] = $var['id'];
				//}
			}
		}


		//print_r($current_variation_ids);
		//exit;

		//echo '<br/><br/><br/><br/><pre>';
		//print_r($_POST['_locations']);
		//echo '</pre>';

		//echo '<br/><br/><br/><br/><pre>';
		//print_r($current_variation_ids);
		//echo '</pre>';

		//exit;

		foreach($_POST['_locations'] as $l_key => $l_val){
			//echo '<br/><br/>looping '.$l_val.'<br/>';

			if(isset($_POST['_location_'.$l_key.'_dates'])){
				foreach($_POST['_location_'.$l_key.'_dates'] as $d_key => $d_val){

					$t_val = $_POST['_location_'.$l_key.'_dates_time'][$d_key];

					//echo $_POST['_location_'.$l_key.'_variation_id'][$d_key].'<br/>';

					//echo 'checking '.$_POST['_location_'.$l_key.'_variation_id'][$d_key].' is in array';
					//print_r($current_variation_ids);
					//echo '<br/><br/>';

					if(in_array($_POST['_location_'.$l_key.'_variation_id'][$d_key], $current_variation_ids)){
						// The variation id
						$variation_id = $_POST['_location_'.$l_key.'_variation_id'][$d_key];

						$key = array_search($variation_id,$current_variation_ids);
						unset($current_variation_ids[$key]);
						//$current_variation_ids = array_values($current_variation_ids);

					}else{
						$variation = array(
						    'post_title'   => 'Product #' . $parent_id . ' location: '.$l_val.' date: '.$d_val.'',
						    'post_content' => '',
						    'post_status'  => 'publish',
						    'post_parent'  => $parent_id,
						    'post_type'    => 'product_variation'
						);
						// The variation id
						$variation_id = wp_insert_post( $variation );

					}


					// Regular Price ( you can set other data like sku and sale price here )
					update_post_meta( $variation_id, '_regular_price', $_POST['_location_'.$l_key.'_dates_cost'][$d_key] );
					update_post_meta( $variation_id, '_price', $_POST['_location_'.$l_key.'_dates_cost'][$d_key] );

					// Assign the size and color of this variation
					//$location_value = strtolower($l_val);
					//$location_value = str_replace(' ', '-', $location_value);
					$location_value = wc_sanitize_taxonomy_name($l_val);

					//$date_value = strtolower($d_val);
					//$date_value = str_replace(' ', '-', $date_value);
					$date_value = wc_sanitize_taxonomy_name($d_val);

					//$time_value = strtolower($t_val);
					//$time_value = str_replace(' ', '-', $time_value);
					$time_value = wc_sanitize_taxonomy_name($t_val);
					

					update_post_meta( $variation_id, 'attribute_' . $location, $location_value );
					update_post_meta( $variation_id, 'attribute_' . $date, $date_value );
					update_post_meta( $variation_id, 'attribute_' . $time, $time_value );
					update_post_meta( $variation_id, '_stock', $_POST['_location_'.$l_key.'_dates_max'][$d_key] );
					update_post_meta( $variation_id, '_manage_stock', 'yes');

					WC_Product_Variable::sync( $parent_id );

				}
			}
		}


		// REMOVE VARIATIONS THAT NO LONGER EXIST
		if(!empty($current_variation_ids)){
			//print_r($current_variation_ids);
			//exit;
			foreach($current_variation_ids as $id){
				wp_delete_post($id);
			}
		}

		// Update parent if variable so price sorting works and stays in sync with the cheapest child
	}

}
add_action( 'woocommerce_process_product_meta_variable', 'save_course_option_field'  );
add_action( 'woocommerce_process_product_meta_external', 'save_course_option_field'  );




/**
 * Hide Attributes data panel.
 */
function hide_attributes_data_panel( $tabs) {
	$tabs['attribute']['class'][] = 'hide_if_course';
	$tabs['shipping']['class'][] = 'hide_if_course';
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'hide_attributes_data_panel' );



/**
 * Add product option
 */
function add_course_product_options( $product_type_options ) {
	$product_type_options['course'] = array(
		'id'            => '_course',
		'wrapper_class' => 'show_if_variable',
		'label'         => __( 'Course', 'woocommerce' ),
		'description'   => __( 'Courses allow you to specify location and dates.', 'woocommerce' ),
		'default'       => 'no'
	);

	$product_type_options['online_course'] = array(
		'id'            => '_online_course',
		'wrapper_class' => 'show_if_variable show_if_simple, show_if_external',
		'label'         => __( 'Online Course', 'woocommerce' ),
		'description'   => __( 'Courses allow you to specify location and dates.', 'woocommerce' ),
		'default'       => 'no'
	);

	$product_type_options['private_course'] = array(
		'id'            => '_private_course',
		'wrapper_class' => 'show_if_variable show_if_simple',
		'label'         => __( 'Private Course', 'woocommerce' ),
		'description'   => __( 'Courses allow you to specify location and dates.', 'woocommerce' ),
		'default'       => 'no'
	);

	$product_type_options['public_course'] = array(
		'id'            => '_public_course',
		'wrapper_class' => 'show_if_variable show_if_simple',
		'label'         => __( 'Public Course', 'woocommerce' ),
		'description'   => __( 'Courses allow you to specify location and dates.', 'woocommerce' ),
		'default'       => 'no'
	);

	return $product_type_options;
}
add_filter( 'product_type_options', 'add_course_product_options' );



/// ADD CUSTOM FIELDS
function product_add_meta_box() {
    add_meta_box( 'product_meta_box_course_specs',
        'Course Specs',
        'display_product_meta_box_course_specs',
        'product'
    );

     add_meta_box( 'product_meta_box_course_outline',
        'Course Outline',
        'display_product_meta_box_course_outline',
        'product'
    );

    add_meta_box( 'product_meta_box_cost_outline',
        'Course Cost Outline',
        'display_product_meta_box_cost_outline',
        'product'
    );

    add_meta_box( 'product_meta_box_additional_options',
        'Additional Options',
        'display_product_meta_additional_options',
        'product'
    );

}

add_action( 'admin_init', 'product_add_meta_box' );

function display_product_meta_box_course_specs(){
	global $post;

    $course_specs =  get_post_meta( $post->ID, 'course_specs', true );
	wp_editor($course_specs,'course_specs');


	echo '<input type="hidden" name="product_flag" value="true" />';
}

function display_product_meta_box_course_outline(){
	global $post;

	$course_outline =  get_post_meta( $post->ID, 'course_outline', true );
	wp_editor($course_outline,'course_outline');

	echo '<input type="hidden" name="product_flag" value="true" />';
}

function display_product_meta_box_cost_outline(){
	global $post;

	$cost_outline =  get_post_meta( $post->ID, 'cost_outline', true );
	wp_editor($cost_outline,'cost_outline');

	echo '<input type="hidden" name="product_flag" value="true" />';
}

function display_product_meta_additional_options(){
	global $post;

	$info_sheet =  get_post_meta( $post->ID, 'info_sheet', true );
	echo '<p>Info Sheet: <input type="text" name="info_sheet" value="'.$info_sheet.'" /></p>';

	$course_into_pdf =  get_post_meta( $post->ID, 'course_into_pdf', true );
	echo '<p>Course Into PDF: <input type="text" name="course_into_pdf" value="'.$course_into_pdf.'" /></p>';

	$demo_url =  get_post_meta( $post->ID, 'demo_url', true );
	echo '<p>Demo URL: <input type="text" name="demo_url" value="'.$demo_url.'" /></p>';

	echo '<input type="hidden" name="product_flag" value="true" />';
}


function update_product_meta_box($post_id, $post ){
    if ( $post->post_type == 'product' ) {
        if (isset($_POST['product_flag'])) {

            if ( isset( $_POST['course_specs'] ) && $_POST['course_specs'] != '' ) {
                update_post_meta( $post_id, 'course_specs', $_POST['course_specs'] );
            }else{
                update_post_meta( $post_id, 'course_specs', '');
            }

            if ( isset( $_POST['course_outline'] ) && $_POST['course_outline'] != '' ) {
                update_post_meta( $post_id, 'course_outline', $_POST['course_outline'] );
            }else{
                update_post_meta( $post_id, 'course_outline', '');
            }

            if ( isset( $_POST['cost_outline'] ) && $_POST['cost_outline'] != '' ) {
                update_post_meta( $post_id, 'cost_outline', $_POST['cost_outline'] );
            }else{
                update_post_meta( $post_id, 'cost_outline', '');
            }

            if ( isset( $_POST['info_sheet'] ) && $_POST['info_sheet'] != '' ) {
                update_post_meta( $post_id, 'info_sheet', $_POST['info_sheet'] );
            }else{
                update_post_meta( $post_id, 'info_sheet', '');
            }

            if ( isset( $_POST['course_into_pdf'] ) && $_POST['course_into_pdf'] != '' ) {
                update_post_meta( $post_id, 'course_into_pdf', $_POST['course_into_pdf'] );
            }else{
                update_post_meta( $post_id, 'course_into_pdf', '');
            }

            if ( isset( $_POST['demo_url'] ) && $_POST['demo_url'] != '' ) {
                update_post_meta( $post_id, 'demo_url', $_POST['demo_url'] );
            }else{
                update_post_meta( $post_id, 'demo_url', '');
            }

        }
    }
}

add_action( 'save_post', 'update_product_meta_box', 10, 2 );










//TEMPLATE HOOKS
//remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
//remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'container_tag_start', 10);
add_action('woocommerce_after_main_content', 'container_tag_end', 100);

function container_tag_start() {
  echo '<div class="container">';
}

function container_tag_end() {
  echo '</div>';


  echo '<div class="container-fluid" style="background-color: #F1F2F2;">';
	  echo '<div class="container">';
	  	echo '<p style="text-align:center">To view industry specific information, please visit our websites.</p>';
	  	echo '<div class="d-flex justify-content-around">';
	  		echo '<a href="'.get_site_url(1).'"><img src="'.get_stylesheet_directory_uri().'/assets/images/industrial-safety-trainers-logo-white-bg.png" /></a>';
	  		echo '<a href="'.get_site_url(2).'"><img src="'.get_stylesheet_directory_uri().'/assets/images/construction-safety-trainers-logo-white-bg.png" /></a>';
	  	echo '</div>';
	  echo '</div>';
  echo '</div>';


}


//ADD COURSE INFORMATION 
add_action('woocommerce_after_single_product_summary', 'course_specific_info', 5);
function course_specific_info(){
	global $product;
	$id = $product->get_id();

	$course_outline = get_post_meta($id,'course_outline',true);
	if($course_outline != ''){
		echo '<div class="woocommerce-content-box-section">';
			echo '<h3 class="woocommerce-content-box">Course Outline</h3>';
			echo $course_outline;
		echo '</div>';
	}
}


//ADD TITLE 
add_action('woocommerce_before_single_product_summary', 'product_title', 5);
function product_title(){
	global $product;
	$id = $product->get_id();

	$title = get_the_title($id);
	$categories = get_the_terms( $post->ID, 'product_cat' );
	if ( $categories && ! is_wp_error( $category ) ){
		foreach($categories as $category){
			$children = get_categories( array ('taxonomy' => 'product_cat', 'parent' => $category->term_id ));
			if ( count($children) == 0 ) {
				// if no children, then echo the category name.
				$the_category = $category->name;
			}
		}
	}


	echo '<div class="woocommerce-title-section">';
		echo '<h3 class="h3">'.$the_category.'</h3>';
		echo '<h1 class="h4">'.$title.'</h2>';
	echo '</div>';
}

// REMOVE SIDEBAR
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// REMOVE TITLE AND RATING IN SUMMARY
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);


//ADD TITLE ABOUT SUMMARY
add_action('woocommerce_single_product_summary', 'woocommerce_single_product_summary_heading', 5);
function woocommerce_single_product_summary_heading(){
	global $product;
	$id = $product->get_id();
	$is_course = get_post_meta($id,'_course',true);

	if($is_course == 'yes'){
		echo  '<h2>Course Details</h2>';
	}else{
		echo  '<h2>Product Details</h2>';
	}
}




// ADD INFO SHEET AND DEMO URL
add_action('woocommerce_after_add_to_cart_button','add_info_demo_buttons',10);
function add_info_demo_buttons(){
	global $product;
	$id = $product->get_id();
	$course_into_pdf = get_post_meta($id,'course_into_pdf',true);
	$demo_url = get_post_meta($id,'demo_url',true);

	if($course_into_pdf != '' || $demo_url != ''){
		echo '<p>';
	}

		if($course_into_pdf != ''){
			echo '<a href="'.$course_into_pdf.'" target="_blank" class="button alt" style="margin-right:10px">Course Into PDF</a>';
		}

		if($demo_url != ''){
			echo '<a href="'.$demo_url.'" target="_blank" class="button alt" style="margin-right:10px">Demo Request</a>';
		}

	if($course_into_pdf != '' || $demo_url != ''){
		echo '</p>';
	}
}