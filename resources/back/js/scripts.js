jQuery(document).ready(function($) {
	
	var __HTML__ = '';
	var __HTML__DELETE__ = '';
	

	$(document).on('click', '.wppc_delete_list', function() {
		
		lid = $(this).attr( 'lid' );
		if( lid.length == 0 ) return;
		
		$('.wppc_popup_delete .wppc_delete_list_confirm').attr( 'lid', lid );
		$('.wppc_popup_delete').fadeIn();
	})
	
	$(document).on('click', '.wppc_popup_delete .wppc_delete_list_confirm', function() {
		
		lid = $(this).attr( 'lid' );	
		if( lid.length == 0 ) return;
		
		__HTML__DELETE__ = $( '.wppc_popup_delete .wppc_popup_box' ).html();
		$('.wppc_popup_delete .wppc_popup_box').html( '<span class="wppc_popup_close"><i class="fa fa-times-circle-o"></i></span> <i class="fa fa-spin fa-cog wppc_loading"></i>' );
		
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:wppc_ajax.wppc_ajaxurl,
		data: {
			"action"	: "wppc_admin_ajax_delete_list",  
			"lid"		: lid,
		},
		success: function( response ) {
			$('.wppc_popup_delete .wppc_popup_box').html( '<span class="wppc_popup_close"><i class="fa fa-times-circle-o"></i></span> <i class="fa fa-check wppc_loading"></i><br>'+response );
		}
			});	
	})
	
		
	$(document).on('click', '.wppc_popup_delete .wppc_popup_box .wppc_popup_close', function() {
		
		$('.wppc_popup_delete').fadeOut();
		
		setTimeout(function(){ 
			if( __HTML__DELETE__.length != 0 ) 
				$('.wppc_popup_new .wppc_popup_box').html( __HTML__DELETE__ );
		}, 400);
	})
	
	
	
	
	
	
	
	$(document).on('click', '.wppc_create_new_list_submit', function() {
		
		__IS_INPUT_ERROR__ = false;
		
		$('.wppc_popup_new .wppc_section_container input[type=text]').each(function(){
			if( $(this).val().length == 0 ){
				$(this).addClass('wppc_input_error');
				__IS_INPUT_ERROR__ = true;
			} else {
				$(this).removeClass('wppc_input_error');
				window[$(this).val()] = 10;
			}
		})
		$('.wppc_popup_new .wppc_section_container select').each(function(){
			if( $(this).val().length == 0 ){
				$(this).addClass('wppc_input_error');
				__IS_INPUT_ERROR__ = true;
			} else {
				$(this).removeClass('wppc_input_error');
			}
		})
		
		if( __IS_INPUT_ERROR__ ) return;
		
		
		
		
		wppc_list_name = $('#wppc_list_name').val();
		wppc_list_company = $('#wppc_list_company').val();
		wppc_list_address = $('#wppc_list_address').val();
		wppc_list_city = $('#wppc_list_city').val();
		wppc_list_state = $('#wppc_list_state').val();
		wppc_list_zip = $('#wppc_list_zip').val();
		wppc_list_country = $('#wppc_list_country').val();
		wppc_list_from_name = $('#wppc_list_from_name').val();
		wppc_list_from_email = $('#wppc_list_from_email').val();
		wppc_list_subject = $('#wppc_list_subject').val();
		wppc_list_language = $('#wppc_list_language').val();
		
		
		__HTML__ = $('.wppc_popup_new .wppc_popup_box').html();
		$('.wppc_popup_new .wppc_popup_box').html( '<span class="wppc_popup_close"><i class="fa fa-times-circle-o"></i></span> <i class="fa fa-spin fa-cog wppc_loading"></i>' );
		
		
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:wppc_ajax.wppc_ajaxurl,
		data: {
			"action"				: "wppc_admin_ajax_create_new_list",  
			"wppc_list_name"		: wppc_list_name,
			"wppc_list_company"		: wppc_list_company,
			"wppc_list_address"		: wppc_list_address,
			"wppc_list_city"		: wppc_list_city,
			"wppc_list_state"		: wppc_list_state,
			"wppc_list_zip"			: wppc_list_zip,
			"wppc_list_country"		: wppc_list_country,
			"wppc_list_from_name"	: wppc_list_from_name,
			"wppc_list_from_email"	: wppc_list_from_email,
			"wppc_list_subject"		: wppc_list_subject,
			"wppc_list_language"	: wppc_list_language,
		},
		success: function( response ) {
			
			$('.wppc_popup_new .wppc_popup_box').html( '<span class="wppc_popup_close"><i class="fa fa-times-circle-o"></i></span> <i class="fa fa-check wppc_loading"></i><br>'+response );
		}
			});	
	
	
	})
	
	
	$(document).on('click', '.wppc_create_new_list', function() {
		$('.wppc_popup_new').fadeIn();
	})
	
	$(document).on('click', '.wppc_popup_new .wppc_popup_box .wppc_popup_close', function() {
		
		$('.wppc_popup_new').fadeOut();
		
		setTimeout(function(){ 
			if( __HTML__.length != 0 ) 
				$('.wppc_popup_new .wppc_popup_box').html( __HTML__ );
		}, 400);
	})
	
	
	$(document).on('click', '.wppc_synchronize_data', function() {

		if( confirm("Do you really want to Synchronize?")){
			
			$(this).text('Synchronizing...');
			
			$.ajax(
				{
			type: 'POST',
			context: this,
			url:wppc_ajax.wppc_ajaxurl,
			data: {"action": "wppc_admin_ajax_synchronize_data"},
			success: function(data) {
				$(this).text('Synchronize');
				$(this).parent().prepend( data );
			}
				});
		}
	})


});	







