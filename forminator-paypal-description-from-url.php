<?php
/**
* Plugin Name: [Forminator] - PayPal Order Description From Form ID.
* Description: Add description to PayPal Transaction based on form id.
* Plugin URI: https://github.com/Kimball31/Forminator-PayPal-Order-Description-From-URL
* Author: Kimball Rexford
* Author URI: https://kimballrexford.com/
* Version: 0.3
* License: GPLv2 or later
* Readme: To install copy file to /wp-content/mu-plugins/ folder
*/

if ( ! defined( 'ABSPATH' ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    return;
}

add_filter( 'forminator_paypal_create_order_request', 'forminator_mu_paypal_description_from_path', 100, 2 );

function forminator_mu_paypal_description_from_path( $request, $data ) {
    
    date_default_timezone_set('US/Eastern');
    
    // defaults:
    $description = '(missing)'; 
    $form_id = 0;

    if(isset($data['form_id'])){
        $form_id = $data['form_id'];
        $form_name = forminator_get_form_name($form_id);
        $description = $form_name;        
    }        

    // set paypal order description 
    $request['purchase_units'][0]['description'] = $description;   
    $request['purchase_units'][0]['reference_id'] = $form_id;  //external ID for the purchase unit
     
    return $request;
}
