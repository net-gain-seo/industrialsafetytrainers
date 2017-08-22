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
	}
}
add_action( 'plugins_loaded', 'register_course_product_type' );


/**
 * Add to product type drop down.
 */
function add_course_product( $types ){
	// Key should be exactly the same as in the class
	$types[ 'course' ] = __( 'Course' );
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
				jQuery( '.options_group.pricing' ).addClass( 'show_if_course' ).show();



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
	global $post;
	?>
	<div id='course_options' class='panel woocommerce_options_panel'>
		<div class='options_group'>

		<div style="padding: 20px;">
			<button class="button button-primary">Add Location</button>
		</div>

		<div style="padding:20px;">
			<div style="margin-bottom: 50px;">
				<p style="text-align: right;"><input name="" value="location 1" /> - <button class="button button-primary">Add Date</button></p>
				<div>
					<table class="widefat fixed">
						<thead>
							<tr>
								<th>Date</th>
								<th class="num">Signed Up</th>
								<th>Maximum</th>
								<th class="num">Export</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>2017-09-01</td>
								<td class="num">2</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
							<tr>
								<td>2017-09-02</td>
								<td class="num">6</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
							<tr>
								<td>2017-09-03</td>
								<td class="num">4</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div style="margin-bottom: 50px;">
				<p style="text-align: right;"><input name="" value="location 1" /> - <button class="button button-primary">Add Date</button></p>
				<div>
					<table class="widefat fixed">
						<thead>
							<tr>
								<th>Date</th>
								<th class="num">Signed Up</th>
								<th>Maximum</th>
								<th class="num">Export</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>2017-09-01</td>
								<td class="num">2</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
							<tr>
								<td>2017-09-02</td>
								<td class="num">6</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
							<tr>
								<td>2017-09-03</td>
								<td class="num">4</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div style="margin-bottom: 50px;">
				<p style="text-align: right;"><input name="" value="location 1" /> - <button class="button button-primary">Add Date</button></p>
				<div>
					<table class="widefat fixed">
						<thead>
							<tr>
								<th>Date</th>
								<th class="num">Signed Up</th>
								<th>Maximum</th>
								<th class="num">Export</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>2017-09-01</td>
								<td class="num">2</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
							<tr>
								<td>2017-09-02</td>
								<td class="num">6</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
							<tr>
								<td>2017-09-03</td>
								<td class="num">4</td>
								<td><input name="" value="10" /></td>
								<td class="num"><button class="button button-primary">Export Attendees</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		

		<?php
			woocommerce_wp_checkbox( array(
				'id' 		=> '_enable_renta_option',
				'label' 	=> __( 'Enable course option X', 'woocommerce' ),
			) );
			woocommerce_wp_text_input( array(
				'id'			=> '_text_input_y',
				'label'			=> __( 'What is the value of Y', 'woocommerce' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'A handy description field', 'woocommerce' ),
				'type' 			=> 'text',
			) );
		?>
			
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_product_data_panels', 'course_options_product_tab_content' );





/**
 * Save the custom fields.
 */
function save_course_option_field( $post_id ) {
	$course_option = isset( $_POST['_enable_renta_option'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_enable_renta_option', $course_option );
	if ( isset( $_POST['_text_input_y'] ) ) :
		update_post_meta( $post_id, '_text_input_y', sanitize_text_field( $_POST['_text_input_y'] ) );
	endif;
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