<?php	


/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	
	
	$WPPC_Functions = new WPPC_Functions();
	
	$wppc_languages_list = $WPPC_Functions->wppc_languages_list();
	$wppc_country_list = $WPPC_Functions->wppc_country_list();
?>

<div class="wppc_popup_container wppc_popup_new">
	<div class="wppc_popup_box">
		<span class="wppc_popup_close"><i class="fa fa-times-circle-o"></i></span>
		<div class="wppc_section">
			<div class="section_title">Please Fillup these Information</div>
		</div>
		<div class="wppc_section_container">
		
			<div class="wppc_section">
				<div class="section_title">List Information</div>
				<div class="section_info">List Name</div>
				<div class="section_input">
					<input type="text" id="wppc_list_name" placeholder="New List Name"/>
				</div>			
			</div>
			
			<div class="wppc_section">
				<div class="section_title">List Contact Information</div>
				
				<div class="section_info">Company Name</div>
				<div class="section_input">
					<input type="text" id="wppc_list_company" placeholder="Company Name"/>
				</div>			
					
				<div class="section_info">Address</div>
				<div class="section_input">
					<input type="text" id="wppc_list_address" placeholder="New town, 73001"/>
				</div>			
				
				<div class="section_info">City</div>
				<div class="section_input">
					<input type="text" id="wppc_list_city" placeholder="New York"/>
				</div>			
				
				<div class="section_info">State</div>
				<div class="section_input">
					<input type="text" id="wppc_list_state" placeholder="New York"/>
				</div>			
				
				<div class="section_info">ZIP</div>
				<div class="section_input">
					<input type="text" id="wppc_list_zip" placeholder="73001"/>
				</div>			
				
				<div class="section_info">Country</div>
				<div class="section_input">
					<select id="wppc_list_country">
						<option value="">Select Country</option>
						<?php
						foreach( $wppc_country_list as $key => $country ){
							echo "<option value='$key'>$country</option>";
						}
						?>
					</select>
				</div>			
			</div>
			
			<div class="wppc_section">
				<div class="section_title">Campaign Information</div>
				
				<div class="section_info">From Name</div>
				<div class="section_input">
					<input type="text" id="wppc_list_from_name" placeholder="John Tunner"/>
				</div>			
					
				<div class="section_info">From Email</div>
				<div class="section_input">
					<input type="text" id="wppc_list_from_email" placeholder="username@yoursite.com"/>
				</div>			
				
				<div class="section_info">Subject</div>
				<div class="section_input">
					<input type="text" id="wppc_list_subject" placeholder="Thank you Email"/>
				</div>			
				
				<div class="section_info">Language</div>
				<div class="section_input">
					<select id="wppc_list_language">
						<option value="">Select Language</option>
						<?php
						foreach( $wppc_languages_list as $key => $language ){
							echo "<option value='$key'>$language</option>";
						}
						?>
					</select>
				</div>			
	
			</div>
			
		</div>
		
		<br><div class="button button-orange wppc_create_new_list_submit">Create Now</div></br>
		
	</div>
</div>


<div class="wppc_popup_container wppc_popup_delete">
	<div class="wppc_popup_box">
		<span class="wppc_popup_close"><i class="fa fa-times-circle-o"></i></span>
		<br>
		<div class="wppc_section">
			<div class="section_title">Do you Really want to Delete this List?</div>
			<div class="section_info" style="color:red;"><b>Note:</b> If you delete a list, you’ll lose the list history—including subscriber activity, unsubscribes, complaints, and bounces. You’ll also lose subscribers’ email addresses, unless you <a href="https://goo.gl/qosn9U" target="_blank">Exported and backed up</a> your list.
			</div>
		</div>
		<center><div class="button button-orange wppc_delete_list_confirm" lid="">Delete Now</div></center></br>
	</div>
</div>