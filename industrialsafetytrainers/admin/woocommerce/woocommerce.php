<?php 
add_filter( 'woocommerce_product_subcategories_hide_empty', 'hide_empty_categories', 10, 1 );
function hide_empty_categories ( $hide_empty ) {
    $hide_empty  =  FALSE;
    // You can add other logic here too
    return $hide_empty;
}












/**
 * Register the custom product type after init
 */
function register_course_product_type() {
	/**
	 * This should be in its own separate file.
	 */
	class WC_Product_course extends WC_Product {
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


/**
 * Add to product type drop down.
 */
function add_course_product( $types ){
	// Key should be exactly the same as in the class
	$types['course'] = __( 'Course' );
	return $types;
}
add_filter( 'product_type_selector', 'add_course_product' );


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
				jQuery('.options_group.pricing').addClass( 'show_if_course' ).show();
				jQuery('.general_options.general_tab').addClass( 'show_if_course' ).show();
				jQuery('.attribute_options.attribute_tab').addClass( 'show_if_course' ).show();
				jQuery('.variations_options.variations_tab').addClass( 'show_if_course' ).show();


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
		'class'		=> array( 'show_if_course', 'show_if_variable_course'  ),
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'custom_product_tabs' );




/**
 * Contents of the course options product tab.
 */
function course_options_product_tab_content() {
	global $woocommerce, $product, $post;
	?>
	<div id='course_options' class='panel woocommerce_options_panel'>
		<div class='options_group'>

			<div style="padding: 20px;">
				<button class="button button-primary">Add Location</button>
			</div>


			<?php 
			$product_variations = new WC_Product_Variable($post->ID);
			$variables = $product_variations->get_available_variations();
			print_r($variables);

			//$available_variations = $product->get_available_variations();
			//print_r($available_variations);



			?>

			<div style="padding:20px;">
				<div style="margin-bottom: 50px;">
					<p style="text-align: right;"><input name="_locations[0]" value="location 1" /> - <button class="button button-primary">Add Date</button></p>
					<div>
						<table class="widefat fixed">
							<thead>
								<tr>
									<th>Date</th>
									<th class="num">Signed Up</th>
									<th>Maximum</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input name="_location_0_dates[]" value="2017-09-01" /></td>
									<td class="num">2</td>
									<td><input name="_location_0_dates_max[]" value="10" /></td>
								</tr>
								<tr>
									<td><input name="_location_0_dates[]" value="2017-09-02" /></td>
									<td class="num">6</td>
									<td><input name="_location_0_dates_max[]" value="10" /></td>
								</tr>
								<tr>
									<td><input name="_location_0_dates[]" value="2017-09-03" /></td>
									<td class="num">4</td>
									<td><input name="_location_0_dates_max[]" value="10" /></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div style="margin-bottom: 50px;">
					<p style="text-align: right;"><input name="_locations[1]" value="location 2" /> - <button class="button button-primary">Add Date</button></p>
					<div>
						<table class="widefat fixed">
							<thead>
								<tr>
									<th>Date</th>
									<th class="num">Signed Up</th>
									<th>Maximum</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input name="_location_1_dates[]" value="2017-09-01" /></td>
									<td class="num">2</td>
									<td><input name="_location_0_dates_max[]" value="10" /></td>
								</tr>
								<tr>
									<td><input name="_location_1_dates[]" value="2017-09-02" /></td>
									<td class="num">6</td>
									<td><input name="_location_0_dates_max[]" value="10" /></td>
								</tr>
								<tr>
									<td><input name="_location_1_dates[]" value="2017-09-03" /></td>
									<td class="num">4</td>
									<td><input name="_location_0_dates_max[]" value="10" /></td>
								</tr>
							</tbody>
						</table>
					</div>
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

		// GET CURRENT VARIATIONS AND MAKE SURE WE DONT RE ADD. 
		$product_variations = new WC_Product_Variable($parent_id);
		$variables = $product_variations->get_available_variations();
		print_r($variables);


		foreach($_POST['_locations'] as $l_key => $l_val){
			if(isset($_POST['_location_'.$l_key.'_dates'])){
				foreach($_POST['_location_'.$l_key.'_dates'] as $d_key => $d_val){

					$variation = array(
					    'post_title'   => 'Product #' . $parent_id . ' location: '.$l_val.' date: '.$d_val.'',
					    'post_content' => '',
					    'post_status'  => 'publish',
					    'post_parent'  => $parent_id,
					    'post_type'    => 'product_variation'
					);
					// The variation id
					$variation_id = wp_insert_post( $variation );

					// Regular Price ( you can set other data like sku and sale price here )
					update_post_meta( $variation_id, '_regular_price', 2 );
					update_post_meta( $variation_id, '_price', 2 );

					// Assign the size and color of this variation
					$location_value = strtolower($l_val);
					$location_value = str_replace(' ', '-', $location_value);

					$date_value = strtolower($d_val);
					$date_value = str_replace(' ', '-', $date_value);

					update_post_meta( $variation_id, 'attribute_' . $location, $location_value );
					update_post_meta( $variation_id, 'attribute_' . $date, $date_value );
				}
			}
		}

		// Update parent if variable so price sorting works and stays in sync with the cheapest child
		WC_Product_Variable::sync( $parent_id );
	}

	/*
	$course_option = isset( $_POST['_enable_renta_option'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_enable_renta_option', $course_option );


	if ( isset( $_POST['_text_input_y'] ) ) :
		update_post_meta( $post_id, '_text_input_y', sanitize_text_field( $_POST['_text_input_y'] ) );
	endif;
	*/
}
add_action( 'woocommerce_process_product_meta_course', 'save_course_option_field'  );
add_action( 'woocommerce_process_product_meta_variable_course', 'save_course_option_field'  );




/**
 * Hide Attributes data panel.
 */
function hide_attributes_data_panel( $tabs) {
	$tabs['attribute']['class'][] = 'hide_if_course';
	$tabs['shipping']['class'][] = 'hide_if_course';
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'hide_attributes_data_panel' );