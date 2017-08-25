<?php 
add_filter( 'woocommerce_product_subcategories_hide_empty', 'hide_empty_categories', 10, 1 );
function hide_empty_categories ( $hide_empty ) {
    $hide_empty  =  FALSE;
    // You can add other logic here too
    return $hide_empty;
}












/**
 * Register the custom product type after init

function register_course_product_type() {
	/**
	 * This should be in its own separate file.
	class WC_Product_course extends WC_Product_Variable {

		public function __construct( $product ) {
			$this->product_type = 'course';
			parent::__construct( $product );
		}

		public function get_type() {
			return 'course';
		}

	}
}
add_action( 'init', 'register_course_product_type' );
 */

/**
 * Add to product type drop down.

function add_course_product( $types ){
	// Key should be exactly the same as in the class
	$types['course'] = __( 'Course' );
	return $types;
}
add_filter( 'product_type_selector', 'add_course_product' );
 */

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


				//Add course Location				

				jQuery('#add-course-location').click(function(){
					var courseLocationCount = jQuery('.course-location').length;
					var nextCount = courseLocationCount + 1;

					var html = '';

					html += '<div style="margin-bottom: 50px;" class="course-location">';
						html += '<p style="text-align: right;">';
							html += '<input name="_locations[location-'+nextCount+']" value="location-'+nextCount+'" /> - <button data-key="location-'+nextCount+'" class="add-course-date button button-primary">Add Date</button> - <button class="remove-course-location button button-primary">Remove Location</button>';
						html += '</p>';
						html += '<div>';
							html += '<table class="widefat fixed">';
								html += '<thead>';
									html += '<tr>';
										html += '<th>ID</th>';
										html += '<th>Date</th>';
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
						html += '<td>';
							html += '<input name="_location_'+key+'_variation_id[]" value="" readonly="true" />';
							html += '<input name="_location_'+key+'_location[]" value="" readonly="true" />';
						html += '</td>';
						html += '<td><input name="_location_'+key+'_dates[]" value="" /></td>';
						html += '<td><input name="_location_'+key+'_dates_max[]" value="" /></td>';
						html += '<td><input name="_location_'+key+'_dates_cost[]" value="" /></td>';
						html += '<td><button class="remove-course-date button button-primary">Remove Date</button></td>';
					html += '</tr>';

					jQuery('.tbody-'+key).append(html);

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
		if(!isset($var_out[$loc_key])){
			$var_out[$loc_key] = array();
		}

		//Store values in location key array
		$var_out[$loc_key][] = array(
			'id' 			=> $var['variation_id'],
			'max_qty'		=> $var['max_qty'],
			'display_price'	=> $var['display_price'],
			'location' 		=> $var['attributes']['attribute_pa_location'],
			'date' 			=> $var['attributes']['attribute_pa_date']
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
								<input name="_locations[<?php echo $key; ?>]" value="<?php echo $key; ?>" /> - 
								<button data-key="<?php echo $key; ?>" class="add-course-date button button-primary">Add Date</button> - 
								<button class="remove-course-location button button-primary">Remove Location</button>
							</p>
							<div>
								<table class="widefat fixed">
									<thead>
										<tr>
											<th>ID</th>
											<th>Date</th>
											<th>Maximum</th>
											<th>Cost</th>
											<th></th>
										</tr>
									</thead>
									<tbody class="tbody-<?php echo $key; ?>">
										<?php foreach($var as $date){ ?>
										<tr>
											<td>
												<input name="_location_<?php echo $key; ?>_variation_id[]" value="<?php echo $date['id']; ?>" readonly="true" />
												<input name="_location_<?php echo $key; ?>_location[]" value="<?php echo $date['location']; ?>" readonly="true" />
											</td>
											<td><input name="_location_<?php echo $key; ?>_dates[]" value="<?php echo $date['date']; ?>" /></td>
											<td><input name="_location_<?php echo $key; ?>_dates_max[]" value="<?php echo $date['max_qty']; ?>" /></td>
											<td><input name="_location_<?php echo $key; ?>_dates_cost[]" value="<?php echo $date['display_price']; ?>" /></td>
											<td><button class="remove-course-date button button-primary">Remove Date</button></td>
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


	if(isset($_POST['_locations'])){

		
		//CONFIGURE ATTRIBUTES
		$location = wc_attribute_taxonomy_name('Location');
		$date = wc_attribute_taxonomy_name('Date');

		$attributes = array(
			$location => array(
				'name' => $location,
				'value' =>'',
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
			)
		);


		//SET PRODUCT ATTRIBUTES
		update_post_meta( $product_id, '_product_attributes', $attributes );



		// Assign location and dates to the main product
		$locationsArray = array();
		$datesArray = array();
		foreach($_POST['_locations'] as $l_key => $l_val){
			$locationsArray[] = $l_val;
			
			if(isset($_POST['_location_'.$l_key.'_dates'])){
				foreach($_POST['_location_'.$l_key.'_dates'] as $d_key => $d_val){
					$datesArray[] = $d_val;
				}
			}
		}
		wp_set_object_terms($product_id, $locationsArray, $location );
		wp_set_object_terms($product_id, $datesArray, $date );

		
		$parent_id = $product_id;

		// GET CURRENT VARIATIONS AND MAKE SURE WE DONT RE-ADD. 
		$current_variations = get_variations($product_id);
		$current_variation_ids = array();
		foreach($current_variations as $var){
			foreach($var as $v){
				$current_variation_ids[] = $v['id'];
			}
		}


		//print_r($current_variation_ids);

		foreach($_POST['_locations'] as $l_key => $l_val){
			if(isset($_POST['_location_'.$l_key.'_dates'])){
				foreach($_POST['_location_'.$l_key.'_dates'] as $d_key => $d_val){

					//echo $_POST['_location_'.$l_key.'_variation_id'][$d_key].'<br/>';

					if(isset($_POST['_location_'.$l_key.'_variation_id']) && in_array($_POST['_location_'.$l_key.'_variation_id'][$d_key], $current_variation_ids)){
						// The variation id
						$variation_id = $_POST['_location_'.$l_key.'_variation_id'][$d_key];

						// Remove variation from current variations array.

						$key = array_search($variation_id,$_POST['_location_'.$l_key.'_variation_id']);
						//echo '$key: '.$key;

						//echo 'unset: '.$key.'<br/>';
						unset($current_variation_ids[$key]);
						$current_variation_ids = array_values($current_variation_ids);

						//print_r($current_variation_ids);

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
					$location_value = strtolower($l_val);
					$location_value = str_replace(' ', '-', $location_value);

					$date_value = strtolower($d_val);
					$date_value = str_replace(' ', '-', $date_value);

					update_post_meta( $variation_id, 'attribute_' . $location, $location_value );
					update_post_meta( $variation_id, 'attribute_' . $date, $date_value );
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
//add_action( 'woocommerce_process_product_meta_variable_course', 'save_course_option_field'  );




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
function add_course_product_option( $product_type_options ) {
	$product_type_options['course'] = array(
		'id'            => '_course',
		'wrapper_class' => 'show_if_variable',
		'label'         => __( 'Course', 'woocommerce' ),
		'description'   => __( 'Courses allow you to specify location and dates.', 'woocommerce' ),
		'default'       => 'no'
	);
	return $product_type_options;
}
add_filter( 'product_type_options', 'add_course_product_option' );