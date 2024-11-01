jQuery(document).ready(function($) {
	
	$(document).on('change', '#wppc_extra_charge', function() {
		
		__SELECTED__ = $(this).val();
		
		$('.woocommerce-cart table.cart td.actions input[name="update_cart"]').prop('disabled', false);
		$('.woocommerce-cart table.cart td.actions input[name="update_cart"]').trigger( "click" );
		
		
		
		// $('.woocommerce form').submit();
		
		// if( __SELECTED__ == 'inside' ) {
			
			$('.wppc_extra_charge_container').fadeOut();
			
			$.ajax(
				{
			type: 'POST',
			context: this,
			url:wppc_ajax.wppc_ajaxurl,
			data: {
				"action" 	: "wppc_ajax_update_cart", 
				"selected"	: __SELECTED__ 
			},
			success: function(data) {
				
				jQuery('body').trigger('update_checkout');
				jQuery('body').trigger('updated_wc_div');
				
				
				
				
			}
				});
			
			
		// }
		// else {
			// $('.wppc_extra_charge_container').fadeIn();
		// }
		
		
	})
	
	/* $(document).on('change', '.wppc_extra_charge_others', function() {
		
		var selected = [];
		
		if( $('#wppc_liftgate_charge').is(":checked") ) {
			selected.push('wppc_liftgate_charge');
		}
		
		if( $('#wppc_callbefore_charge').is(":checked") ) {
			selected.push('wppc_callbefore_charge');
		}

		
			$.ajax(
				{
			type: 'POST',
			context: this,
			url:wppc_ajax.wppc_ajaxurl,
			data: {
				"action" 	: "wppc_ajax_update_cart2", 
				"selected"	: selected,
			},
			success: function(data) {

				jQuery('body').trigger('update_checkout');
			}
				});
			
	})
	 */
	
		
		
});