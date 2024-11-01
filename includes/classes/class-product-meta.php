<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WPPC_Product_meta{
	
	public function __construct(){
		add_action('add_meta_boxes', array($this, 'meta_boxes_product'));
		// add_action('save_post', array($this, 'meta_boxes_product_save'));
		// add_action( 'post_submitbox_misc_actions', array($this, 'woc_meta_box_status_function') );
	}
	
	public function meta_boxes_product($post_type) {
		$post_types = array('product');
		if (in_array($post_type, $post_types)) {
			add_meta_box('wppc_product_metabox',__('WPPM Data Box',WPPC_TEXT_DOMAIN),
				array($this, 'woc_meta_box_function'),
				$post_type,'side','high');		
		}
	}
	
	
	public function woc_meta_box_function($post) {
        wp_nonce_field('woc_nonce_check', 'woc_nonce_check_value_hour');
		
		
		
   	}
	
	public function meta_boxes_product_save($post_id){
		if (!isset($_POST['woc_nonce_check_value_hour'])) return $post_id;
		$nonce = $_POST['woc_nonce_check_value_hour'];
		
	 	if (!wp_verify_nonce($nonce, 'woc_nonce_check')) return $post_id;
	 	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 	if ('page' == $_POST['post_type']) if (!current_user_can('edit_page', $post_id)) return $post_id;
		else if (!current_user_can('edit_post', $post_id)) return $post_id;
				
		$products_meta = stripslashes_deep($_POST['products_meta']);
		update_post_meta($post_id, 'products_meta', $products_meta);		
	}
} new WPPC_Product_meta();