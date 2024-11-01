<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

class WPPC_Settings_menu{

	public function __construct(){
		add_action('admin_menu', array( $this, 'wppc_menu_init' ));
	}

	public function wppc_menu_init() {
		add_menu_page( 
			__('WooPPChimp',WPPC_TEXT_DOMAIN), 
			__('WooPPChimp',WPPC_TEXT_DOMAIN), 
			'manage_options', 'wppc_settings', 
			array( $this, 'wppc_menu_settings' ), 
			'dashicons-email-alt', 30
		);
		
		add_submenu_page( 
			'wppc_settings',
			__('Email List',WPPC_TEXT_DOMAIN),
			__('Email List',WPPC_TEXT_DOMAIN), 
			'manage_options', 'wppc_email_list', 
			array( $this, 'wppc_menu_email_list' )
		);
	}
	
	public function wppc_menu_settings(){	
		include( WPPC_PLUGIN_DIR. 'templates/menus/menu-settings.php');
	}
	
	public function wppc_menu_email_list(){	
		include( WPPC_PLUGIN_DIR. 'templates/menus/menu-email-list.php');
	}
	
	public function wppc_menu_license_key(){	
		include( WPPC_PLUGIN_DIR. 'templates/menus/menu-license-key.php');
	}
	
	
	
} new WPPC_Settings_menu();