<?php

// ENQUEUE STYLES AND SCRIPTS
function sobeys_scripts()
{
    wp_enqueue_style('sobeys_style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'sobeys_scripts');


function course_registration_form()
{
    global $wpdb;
    $current = get_current_blog_id();
    $currentBlogUrl = get_bloginfo('url');
    switch_to_blog(1);

    $return = '';
    $errors = [];

    if (!isset($_GET['variation'])) {
        return '<p>No course selected</p>';
    }

    $productVariation = new VariationInformationRegistration($_GET['variation']);

    if (isset($_POST['submit_registration'])) {
        $valid = $productVariation->validateForm($_POST);
        if ($valid) {
            // IF valid submit form
            $submitted = $productVariation->submitForm($_POST);
        }
    }

    // If submitted show thank you msg!
    if ($productVariation->formSubmitted) {
        $return .= '<div class="row">';
        $return .= '<div class="col-12">';
        $return .= '<p>Thank You! Some one will be in contact with you to confirm your registration.</p>';
        $return .= '</div>';
        $return .= '</div>';
        return $return;
    }

    // If Errors... show them
    if (!empty($productVariation->validationErrors)) {
        $return .= '<div class="row">';
        $return .= '<div class="col-12">';
        foreach ($productVariation->validationErrors as $error) {
            $return .= '<p style="color:red">' . $error . '</p>';
        }
        $return .= '</div>';
        $return .= '</div>';
    }

    $return .= '<div class="row">';
    $return .= '<div class="col-4">';
    $return .= '<p><strong>Product: </strong>' . $productVariation->title . '<br/>';
    $return .= '<strong>Date: </strong>' . $productVariation->date . '<br/>';
    $return .= '<strong>Location: </strong>' . $productVariation->location . '<br/>';
    $return .= '<strong>Time: </strong>' . $productVariation->time . '<br/>';
    $return .= '<strong>Address: </strong>' . $productVariation->address . '<br/>';
    $return .= '</div>';
    $return .= '<div class="col-8">';
    $return .= '
                <div>
                    <form method="post" action="">
                        <div>
                            <label>Name</label>
                            <input type="text" name="registrant_name" value="' . (isset($_POST['registrant_name']) ? $_POST['registrant_name'] : "") . '" />
                        </div>
                        <div>
                            <label>Email</label>
                            <input type="text" name="registrant_email" value="' . (isset($_POST['registrant_email']) ? $_POST['registrant_email'] : "") . '" />
                        </div>
                        <div>
                            <label>Phone Number</label>
                            <input type="text" name="registrant_phone_number" value="' . (isset($_POST['registrant_phone_number']) ? $_POST['registrant_phone_number'] : "") . '" />
                        </div>
                        <div>
                            <label>Store Name</label>
                            <input type="text" name="store_name" value="' . (isset($_POST['store_name']) ? $_POST['store_name'] : "") . '" />
                        </div>
                        <div>
                            <label>Store Number</label>
                            <input type="text" name="store_number" value="' . (isset($_POST['store_number']) ? $_POST['store_number'] : "") . '" />
                        </div>
                        <div>
                            <label>Number Of Participants</label>
                            <input type="number" name="number_of_participants" max="' . $productVariation->max . '" value="' . (isset($_POST['number_of_participants']) ? $_POST['number_of_participants'] : "") . '" />
                        </div>
                        <div>
                            <label>Participant Names</label>
                            <textarea name="participant_names">' . (isset($_POST['participant_names']) ? $_POST['participant_names'] : "") . '</textarea>
                        </div>
                        <div>
                            <input type="submit" name="submit_registration" value="Submit" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            ';
    $return .= '</div>';

    $return .= '</div>';

    switch_to_blog($current);
    return $return;
}
add_shortcode('course_registration_form', 'course_registration_form');


class VariationInformationRegistration
{
    public $title = '';
    public $date = '';
    public $location = '';
    public $time = '';
    public $address = '';
    public $max = '';


    public $validationErrors = [];

    public $registrant_name = '';
    public $registrant_email = '';
    public $registrant_phone_number = '';
    public $store_name = '';
    public $store_number = '';
    public $number_of_participants = '';
    public $participant_names = '';

    public $formSubmitted = false;

    function __construct($variationId)
    {
        global $wpdb;
        $product_variation = new WC_Product_Variation($variationId);

        // Date, time, location, etc
        $productAtts = $product_variation->get_variation_attributes();

        $meta_attribute_pa_address = get_post_meta($_GET['variation'], 'attribute_pa_address', true);
        $address_term = $wpdb->get_row('SELECT t.*, tt.*
                FROM ' . $wpdb->terms . ' AS t
                INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt ON t.term_id = tt.term_id
                WHERE slug = "' . $meta_attribute_pa_address . '"');

        $meta_attribute_pa_time = get_post_meta($_GET['variation'], 'attribute_pa_time', true);
        $time_term = $wpdb->get_row('SELECT t.*, tt.*
                FROM ' . $wpdb->terms . ' AS t
                INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt ON t.term_id = tt.term_id
                WHERE slug = "' . $meta_attribute_pa_time . '"');

        $meta_attribute_pa_location = get_post_meta($_GET['variation'], 'attribute_pa_location', true);
        $location_term = $wpdb->get_row('SELECT t.*, tt.*
                FROM ' . $wpdb->terms . ' AS t
                INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt ON t.term_id = tt.term_id
                WHERE slug = "' . $meta_attribute_pa_location . '"');


        $this->title = $product_variation->get_title();
        $this->date = $productAtts['attribute_pa_date'];
        $this->location = $location_term->name;
        $this->time = $time_term->name;
        $this->address = $address_term->name;
        $this->max = $product_variation->get_stock_quantity();
    }

    public function validateForm($post)
    {
        //Vali
        if (!isset($post['registrant_name']) || $post['registrant_name'] == '') {
            $this->validationErrors[] = 'Please provide a name.';
        }
        if (!isset($post['registrant_email']) || $post['registrant_email'] == '') {
            $this->validationErrors[] = 'Please provide a email.';
        }
        if (!isset($post['registrant_phone_number']) || $post['registrant_phone_number'] == '') {
            $this->validationErrors[] = 'Please provide a phone number.';
        }
        if (!isset($post['store_name']) || $post['store_name'] == '') {
            $this->validationErrors[] = 'Please provide a store name.';
        }
        if (!isset($post['store_number']) || $post['store_number'] == '') {
            $this->validationErrors[] = 'Please provide a store number.';
        }
        if (!isset($post['number_of_participants']) || $post['number_of_participants'] == '') {
            $this->validationErrors[] = 'Please provide the number or participants that will be attending the course.';
        }
        if (!isset($post['participant_names']) || $post['participant_names'] == '') {
            $this->validationErrors[] = 'Please provide the participant names.';
        }

        if (isset($post['number_of_participants']) && $post['number_of_participants'] > $this->max) {
            $this->validationErrors[] = 'To many participants';
        }

        // If no errors return, else error out. 
        if (empty($this->validationErrors)) {
            return true;
        }
        return false;
    }


    public function submitForm($post)
    {
        $message = '';


        $message .= '<p><strong>Name: </strong>' . $post['registrant_name'] . '<br/>';
        $message .= '<strong>Date: </strong>' . $post['registrant_email'] . '<br/>';
        $message .= '<strong>Phone Number: </strong>' . $post['registrant_phone_number'] . '<br/>';
        $message .= '<strong>Store Name: </strong>' . $post['store_name'] . '<br/>';
        $message .= '<strong>Store Number: </strong>' . $post['store_number'] . '<br/>';
        $message .= '<strong>Time: </strong>' . $post['number_of_participants'] . '<br/>';
        $message .= '<strong>Participant Names: <br/>
            </strong>' . $post['participant_names'] . '<br/><br/>';

        $message .= '<p><strong>Product: </strong>' . $this->title . '<br/>';
        $message .= '<strong>Date: </strong>' . $this->date . '<br/>';
        $message .= '<strong>Location: </strong>' . $this->location . '<br/>';
        $message .= '<strong>Time: </strong>' . $this->time . '<br/>';
        $message .= '<strong>Address: </strong>' . $this->address . '<br/>';
        $message .= '<strong>Maximun Qty: </strong>' . $this->max . '</p>';

        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail('andrewm@netgainseo.com', 'Sobeys Registration', $message, $headers);

        $this->formSubmitted = true;

        return true;
    }
}




function course_price($atts, $content)
{
    extract(shortcode_atts(array(
        'price'         => ''
    ), $atts));

    $return = '<div class="course-detail-section price">
        <div>
            <span>Price*</span>
            <p style="text-align: center; font-size: 20px;">
                <strong>'.$price.'<br></strong>
                per person
            </p>
        </div>
    </div>';

    return $return;
}
add_shortcode('course_price', 'course_price');
