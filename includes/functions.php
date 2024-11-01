<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 




add_action( 'woocommerce_order_status_completed','woocommerce_order_status_completed_function', 99 );
function woocommerce_order_status_completed_function( $order_id ){
	
	update_option( 'order_id', $order_id );
	
	if( empty( $order_id ) ) return;
	$order = new WC_Order( $order_id );
	
	update_option( 'order', $order );
	
	$order_items = $order->get_items();
	if( empty( $order_items ) ) return;
	
	$data = array();
	$data['cus_first_name']	= $order->billing_first_name; 
	$data['cus_last_name']	= $order->billing_last_name;
	$data['cus_email'] 		= $order->billing_email;
	$data['cus_status']	    = 'subscribed'; // "subscribed","unsubscribed","cleaned","pending"
	
	
	update_option( 'order_items', $order_items );

	foreach( $order_items as $item ){
		
		$product_id = isset( $item['product_id'] ) ? $item['product_id'] : 0;
		if( empty( $product_id ) ) continue; 
		
		$_list_id = get_post_meta( $product_id, '_list_id', true );
		if( empty( $_list_id ) ) continue;
		
		$data['product_id']	= $product_id;
		$data['wppc_list_id']	= $_list_id;
		
		update_option( 'data', $data );
		
		wppc_add_email_in_self_server($data);
		wppc_add_email_in_mailchimp($data);
	}
}

function wppc_add_email_in_self_server( $data = array() ){
	
	$cus_first_name	= isset( $data['cus_first_name'] ) ? $data['cus_first_name'] : '';
	$cus_last_name 	= isset( $data['cus_last_name'] ) ? $data['cus_last_name'] : '';
	$cus_email 		= isset( $data['cus_email'] ) ? $data['cus_email'] : '';
	$cus_status 	= isset( $data['cus_status'] ) ? $data['cus_status'] : '';
	$product_id 	= isset( $data['product_id'] ) ? $data['product_id'] : '';
	
	$_this_product_subscribers = array();
	$_this_product_subscribers = get_post_meta( $product_id, '_this_product_subscribers', true );

	$_this_product_subscribers[$cus_email] = $data;
	update_post_meta( $product_id, '_this_product_subscribers', $_this_product_subscribers );

	// if( !in_array( $cus_email, $_this_product_subscribers ) ){
		// $_this_product_subscribers[] = $cus_email;
		// update_option( $product_id, '_this_product_subscribers', $_this_product_subscribers );
	// }
}

function wppc_add_email_in_mailchimp( $data = array() ){

	$cus_first_name	= isset( $data['cus_first_name'] ) ? $data['cus_first_name'] : '';
	$cus_last_name 	= isset( $data['cus_last_name'] ) ? $data['cus_last_name'] : '';
	$cus_email 		= isset( $data['cus_email'] ) ? $data['cus_email'] : '';
	$cus_status 	= isset( $data['cus_status'] ) ? $data['cus_status'] : '';
	$wppc_list_id 	= isset( $data['wppc_list_id'] ) ? $data['wppc_list_id'] : '';
	
	$wppc_api_key 	= get_option('wppc_api_key');
	if( empty( $wppc_api_key ) ) return;
	
	$wppc_member_id = md5(strtolower( $cus_email ));
    $wppc_dta_cntre = substr($wppc_api_key,strpos($wppc_api_key,'-')+1);

    $url = 'https://' . $wppc_dta_cntre . '.api.mailchimp.com/3.0/lists/' . $wppc_list_id . '/members/' . $wppc_member_id;
    $json = json_encode([
        'email_address' => $cus_email,
        'status'        => $cus_status, 
        'merge_fields'  => [
            'FNAME'     => $cus_first_name,
            'LNAME'     => $cus_last_name
        ]
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $wppc_api_key);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 

    $result = curl_exec($ch);
    // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	update_option( 'check_data', $result );
    curl_close($ch);
}

function wppc_woocommerce_product_data_tabs( $tabs ){
	
	$tabs['wppc'] = array(
		'label'  => __( 'WooPPChimp', 'woocommerce' ),
		'target' => 'product_wppc',
		'class'  => array(),
	);
	
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'wppc_woocommerce_product_data_tabs' );



function wppc_woocommerce_product_data_panels(){
	
	echo '<div id="product_wppc" class="panel woocommerce_options_panel hidden">';
	
	$wppc_email_lists = get_option( 'wppc_email_lists' );
	if( empty( $wppc_email_lists ) ) $wppc_email_lists = array();
	
	$options = array();
	$options[''] = __( 'Select a List', 'woocommerce' );
	
	foreach( $wppc_email_lists as $list ) {
		
		$options[$list->id] = $list->name;
	}	
			
	woocommerce_wp_select( 
		array( 
			'id' 			=> '_list_id', 
			'label' 		=> __( 'Mailchimp Email List', 'woocommerce' ), 
			'description' 	=> __( 'Choose a Email list of Mailchimp for this product', 'woocommerce' ), 
			'options' 		=> $options,
			// 'value' 		=> 'b9dfe5ee81',
		)
	);
	
	echo '</div>';
}
add_action( 'woocommerce_product_data_panels', 'wppc_woocommerce_product_data_panels', 99 );


function wppc_save_product_email_list_id_function( $post_id ){
	
	$_list_id = isset( $_POST['_list_id'] ) ? sanitize_post_field( $_POST['_list_id'] ) : '';
	
	if( !empty( $_list_id ) ) {
		
		update_post_meta( $post_id, '_list_id', $_list_id );
	}
}
add_action( 'woocommerce_process_product_meta', 'wppc_save_product_email_list_id_function' );



function wppc_admin_ajax_synchronize_data(){

	$wppc_email = get_option('wppc_email');
	$wppc_api_key = get_option('wppc_api_key');
	
	$data 	= array( 'fields' => 'lists' );
	$url 	= 'https://' . substr($wppc_api_key,strpos($wppc_api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/';
	$mch 	= curl_init();
	$hdrs 	= array(
		'Content-Type: application/json',
		'Authorization: Basic '.base64_encode( 'user:'. $wppc_api_key )
	);
	
	curl_setopt($mch, CURLOPT_URL, $url );
	curl_setopt($mch, CURLOPT_HTTPHEADER, $hdrs);
	curl_setopt($mch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($mch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($mch, CURLOPT_TIMEOUT, 10);
	curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false);
 
	$result = curl_exec($mch);
	$result = json_decode($result);
	$wppc_email_lists = !empty( $result->lists ) ? $result->lists : array();
	
	update_option('wppc_email_lists', $wppc_email_lists);
	
	echo '<p>Synchronization successfull.</p>';
	
	die();
}
add_action('wp_ajax_wppc_admin_ajax_synchronize_data', 'wppc_admin_ajax_synchronize_data');
add_action('wp_ajax_nopriv_wppc_admin_ajax_synchronize_data', 'wppc_admin_ajax_synchronize_data');



function wppc_admin_ajax_create_new_list(){

	$wppc_list_name 		= isset( $_POST['wppc_list_name'] ) ? sanitize_text_field( $_POST['wppc_list_name'] ) : '';
	$wppc_list_company 		= isset( $_POST['wppc_list_company'] ) ? sanitize_text_field( $_POST['wppc_list_company'] ) : '';
	$wppc_list_address 		= isset( $_POST['wppc_list_address'] ) ? sanitize_text_field( $_POST['wppc_list_address'] ) : '';
	$wppc_list_city 		= isset( $_POST['wppc_list_city'] ) ? sanitize_text_field( $_POST['wppc_list_city'] ) : '';
	$wppc_list_state 		= isset( $_POST['wppc_list_state'] ) ? sanitize_text_field( $_POST['wppc_list_state'] ) : '';
	$wppc_list_zip 			= isset( $_POST['wppc_list_zip'] ) ? sanitize_text_field( $_POST['wppc_list_zip'] ) : '';
	$wppc_list_country 		= isset( $_POST['wppc_list_country'] ) ? sanitize_text_field( $_POST['wppc_list_country'] ) : '';
	$wppc_list_from_name 	= isset( $_POST['wppc_list_from_name'] ) ? sanitize_text_field( $_POST['wppc_list_from_name'] ) : '';
	$wppc_list_from_email 	= isset( $_POST['wppc_list_from_email'] ) ? sanitize_email( $_POST['wppc_list_from_email'] ) : '';
	$wppc_list_subject 		= isset( $_POST['wppc_list_subject'] ) ? sanitize_text_field( $_POST['wppc_list_subject'] ) : '';
	$wppc_list_language 	= isset( $_POST['wppc_list_language'] ) ? sanitize_text_field( $_POST['wppc_list_language'] ) : '';
	
	// echo '<pre>'; print_r( $_POST ); echo '</pre>';
	
	$wppc_api_key	= get_option('wppc_api_key');
	$url 			= 'https://' . substr($wppc_api_key,strpos($wppc_api_key,'-')+1) . '.api.mailchimp.com/3.0/lists';

	$data 			= array(
		'apikey'	=> $wppc_api_key,
		'name' 		=> $wppc_list_name,
		'contact'	=> array(
			'company'	=> $wppc_list_company,
			'address1'	=> $wppc_list_address,
			'city'		=> $wppc_list_city,
			'state'		=> $wppc_list_state,
			'zip'		=> $wppc_list_zip,
			'country'	=> $wppc_list_country,
		),
		'permission_reminder' => "pr",
		'campaign_defaults' => array(
			'from_name'		=> $wppc_list_from_name,
			'from_email'	=> $wppc_list_from_email,
			'subject'		=> $wppc_list_subject,
			'language'		=> $wppc_list_language,
		),
		'email_type_option'	=> false,
	);
	$json_data = json_encode($data);


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Basic '. base64_encode( 'user:'.$wppc_api_key ) ));
	curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

	$result = curl_exec($ch);
	$result = json_decode($result);
	
	echo '<div class="wppc_section" style="text-align:center;"><div class="section_info">Please Synchronize Data<br>
	<a href="admin.php?page=wppc_settings#wppc_synchronize_data">Synchronize</a></div></div>';
	
	die();
}
add_action('wp_ajax_wppc_admin_ajax_create_new_list', 'wppc_admin_ajax_create_new_list');
add_action('wp_ajax_nopriv_wppc_admin_ajax_create_new_list', 'wppc_admin_ajax_create_new_list');



function wppc_admin_ajax_delete_list(){

	$lid = isset( $_POST['lid'] ) ? sanitize_text_field( $_POST['lid'] ) : '';
	
	$wppc_api_key	= get_option('wppc_api_key');
	$url 			= 'https://'.substr($wppc_api_key,strpos($wppc_api_key,'-')+1).'.api.mailchimp.com/3.0/lists/'.$lid;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	
	curl_setopt($ch, CURLOPT_USERPWD, "anystring" . ":" . "$wppc_api_key");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
	$result = curl_exec($ch);
	curl_close ($ch);
	
	echo '<pre>'; print_r( $result ); echo '</pre>';
	
	echo '<div class="wppc_section" style="text-align:center;"><div class="section_info">Please Synchronize Data<br>
	<a href="admin.php?page=wppc_settings#wppc_synchronize_data">Synchronize</a></div></div>';
	
	die();
}
add_action('wp_ajax_wppc_admin_ajax_delete_list', 'wppc_admin_ajax_delete_list');
add_action('wp_ajax_nopriv_wppc_admin_ajax_delete_list', 'wppc_admin_ajax_delete_list');



	