<?php
/*
	Plugin Name: WooCommerce - WooPPChimp
	Plugin URI:	https://www.pluginbazar.net/
	Description: This WooCommerce addon let's you Easily assign customers to specific lists based on the products they purchase. This addon is perfect for when you want to send specific newsletters, sale announcements, product releases, events to your customers. It is a better way to market your products, services and provide your customers with what they need.
	Version: 1.0.10
	Author: wayne-hatter-1,pluginbazar
	Text Domain: woc-per-product-chimp
	Author URI: https://yourwpmadesimple.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access


class WooCommercePerProductMailchimp{
	
	public function __construct(){

		$this->wppc_define_constants();
		$this->wppc_define_classes();
		$this->wppc_define_functions();
		
		add_action( 'admin_enqueue_scripts', array( $this, 'wppc_admin_scripts' ) );
	}
	
	public function wppc_define_functions(){
		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
	}
	
	public function wppc_define_classes(){
		
		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-settings.php');			
		require_once( plugin_dir_path( __FILE__ ) . 'includes/classes/class-functions.php');			
	}
	
	public function wppc_define_constants(){
		$this->wppc_define('WPPC_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
		$this->wppc_define('WPPC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		$this->wppc_define('WPPC_TEXT_DOMAIN', 'woo-per-product-chimp' );
	}
	
	private function wppc_define($KEY, $VALUE){
		if( !defined( $KEY ) ) define( $KEY, $VALUE );
	}
	
	public function wppc_front_scripts(){
		wp_enqueue_script('jquery' );
		wp_enqueue_script('wppc_front_js', plugins_url( 'resources/front/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script('wppc_front_js', 'wppc_ajax', array( 'wppc_ajaxurl' => admin_url( 'admin-ajax.php')));
	}

	public function wppc_admin_scripts(){
		
		wp_enqueue_script('wppc_admin_js', plugins_url( '/resources/back/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'wppc_admin_js', 'wppc_ajax', array( 'wppc_ajaxurl' => admin_url( 'admin-ajax.php')));
		
		wp_enqueue_style('font-awesome', WPPC_PLUGIN_URL.'resources/both/css/font-awesome.css');
		
		wp_enqueue_style('wppc_admin_style', WPPC_PLUGIN_URL.'resources/back/css/style.css');
	}
	
} new WooCommercePerProductMailchimp();