<?php
/**
* Plugin Name: [Forminator] - PayPal Order Description From URL.
* Description: Add description to PayPal Transaction based on form url.
* Plugin URI: https://kimballrexford.com/
* Author: Kimball Rexford
* Author URI: https://kimballrexford.com/
* Version: 0.1
* License: GPLv2 or later
* Readme: To install copy file to /wp-content/mu-plugins/ folder
*/

if ( ! defined( 'ABSPATH' ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    return;
}

add_filter( 'forminator_paypal_create_order_request', 'forminator_mu_paypal_description_from_path', 100, 2 );

function forminator_mu_paypal_description_from_path( $request, $data ) {
    parse_str( $data['form_fields'], $form_fields );
    
    // default description set to the domain name (without schema or path)
    $description = parse_url( get_site_url(), PHP_URL_HOST );

    // make description from the forms path
    if ( isset( $form_fields['current_url'] ) ) {

        // get form path for making description
        $form_url_path = parse_url( $form_fields['current_url'], PHP_URL_PATH );

        // remove everything that is not text or numbers from path
        $description = preg_replace( '/[^a-zA-Z0-9]/', ' ', $form_url_path );  

        // remove extra spaces from description and trim
        $description = trim( preg_replace( '/\s+/', ' ', $description ) ); 

        // capitalize words in description
        $description = ucwords(strtolower($description));         

        // cut description at 127 characters max, per spec at https://developer.paypal.com/docs/api/orders/v2/
        $description = substr( $description, 0, 127 );        

    }

    // set paypal description 
    $request['purchase_units'][0]['description'] = $description;     
     
    return $request;
}
