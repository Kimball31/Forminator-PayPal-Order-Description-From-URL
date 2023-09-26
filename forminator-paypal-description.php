<?php
/**
* Plugin Name: [Forminator] - PayPal Order Description From Form Data.
* Description: Add description to PayPal Transaction based on form submitter data.
* Plugin URI: https://kimballrexford.com/
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

    // defaults:
    $form_id = 0;
    $form_name = '';      

    if(isset($data['form_id'])){
        $form_id = $data['form_id'];
        $form_name = forminator_get_form_name($form_id);

        // set paypal purchase_units details         
        if(empty($form_name)){

            $request['purchase_units'][0]['description'] = "form_id $form_id";
            $request['purchase_units'][0]['reference_id'] = $form_id;  

        }else{

            $request['purchase_units'][0]['description'] = $form_name;
            $request['purchase_units'][0]['reference_id'] = $form_id;              

        }

    }       

    return $request;
}
