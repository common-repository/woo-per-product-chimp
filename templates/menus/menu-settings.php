<?php	
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	$wppc_email = get_option('wppc_email');
	$wppc_api_key = get_option('wppc_api_key');


$nonce = isset( $_POST['wppc_settings_nonce_check_value'] ) ? $_POST['wppc_settings_nonce_check_value'] : '';

if ( !empty( $nonce ) && wp_verify_nonce($nonce, 'wppc_settings_nonce_check') ) {

	if( ! empty( $_POST['wppc_form_hidden'] ) ):	if( $_POST['wppc_form_hidden'] == 'Y' ) :
	
		$wppc_email		= sanitize_email( $_POST['wppc_email'] );
		$wppc_api_key	= sanitize_text_field( $_POST['wppc_api_key'] );
		
		if ( current_user_can('manage_options') ) {
			
			update_option( 'wppc_email', $wppc_email );
			update_option( 'wppc_api_key', $wppc_api_key );
			?><div class="updated"><p><strong><?php _e('Changes Saved.', WPPC_TEXT_DOMAIN ); ?></strong></p></div><?php
		}
		else {
			?><div class="error"><p><strong><?php _e('Permission Denied!!!', WPPC_TEXT_DOMAIN ); ?></strong></p></div><?php
		}
		
	endif; endif;
}
	
?>

<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div>
	<h2>WooCommerce Per Product Mailchimp - Settings</h2><br>
		
	<form class="wppc_settings_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="wppc_form_hidden" value="Y" />
		<?php wp_nonce_field('wppc_settings_nonce_check', 'wppc_settings_nonce_check_value'); ?>
		
		<div class="wppc_section">
			<div class="section_title">Mailchim Account E-Mail</div>
			<div class="section_info">
				Pelase enter your Mailchimp account Email Address<br>
				It will helps better to synchronosize data <b>But You can leave this section.</b>
			</div>
			<div class="section_input"><input value="<?php echo $wppc_email; ?>" size="50" type="email" name="wppc_email" placeholder="username@yoursite.com" /></div>
		</div>
		
		<div class="wppc_section">
			<div class="section_title">Mailchim Account API KEY</div>
			<div class="section_info">
				Pelase enter your Mailchimp account API key to synchronise data.<br>
				You will find the Key from here <b>https://us14.admin.mailchimp.com/account/api/</b>
			</div>
			<div class="section_input"><input value="<?php echo $wppc_api_key; ?>" size="50" type="text" required="required" name="wppc_api_key" placeholder="d0f0d3467d5acc7816d9801eb78dd81a-us14" /></div>
		</div>
		
		<div class="wppc_section" id="wppc_synchronize_data">
			<div class="section_title">Synchronize Data</div>
			<div class="section_info">
				Click the button to synchronize your shop with Mailchimp<br>
				<b>Remember: Do it after each single changes on Your Mailchimp Account</b>
			</div>
			<div class="section_input"><div class="wppc_synchronize_data button">Synchronize</div></div>
		</div>
		
		<div class="wppc_section">
			<br>
			<div class="section_input"><input id="single-optin" type="checkbox" name="single-optin" value="single optin" checked> 
			<label for="single-optin">Uncheck this box if you want to use double optin.</label></div>
		</div>
		
		
		
		<br>
		<input class="button button-orange" type="submit" name="Submit" value="<?php _e('Save Changes',WPPC_TEXT_DOMAIN ); ?>" />
	</form>
	
	<div class="wppc_help_container">
	
		<div class="wppc_section">
			<div class="section_title">Getting Your API Key</div>
			<div class="section_info">
				<h2>Step 1: Go to 'Acount'</h2>
				<img style="border:4px dotted #ccc" src="<?php echo WPPC_PLUGIN_URL; ?>resources/images/step1.jpg">
				<h2>Step 2: Click on 'Extras' then choose 'API Keys'</h2>
				<img style="border:4px dotted #ccc" src="<?php echo WPPC_PLUGIN_URL; ?>resources/images/step2.jpg">			
				<h2>Step 3: Click the 'Create Key' button</h2>
				<img style="width:95%;border:4px dotted #ccc" src="<?php echo WPPC_PLUGIN_URL; ?>resources/images/step3.jpg">
				<h2>Step 4: 'Copy and Paste' your API Key into the API KEY field. </h2>
			</div>
		</div>

	</div>
		
</div>