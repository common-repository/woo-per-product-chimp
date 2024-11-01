<?php	


/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	
	$lid = isset( $_GET['lid'] ) ? $_GET['lid'] : '';
	
?>

<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div>
	<h2>WooCommerce Per Product Mailchimp - E-Mail List</h2><br>
	
	<div class="button button-orange wppc_create_new_list">Create New List</div></br><br>
	
	<?php
	if( empty( $lid ) ){
		include 'email-list/list.php';
	}
	else {
		include 'email-list/edit.php';
	}
	?>
		
</div>


<?php include 'email-list/popup.php'; ?>