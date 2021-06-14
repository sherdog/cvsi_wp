
jQuery( document ).ready( function() {
	"use strict";
	// Hide admin notice
	jQuery('.trx_addons_theme_panel_theme_inactive .trx_addons_hide_notice')
		.on('click', function (e) {
			jQuery(this).parents('.trx_addons_theme_panel_theme_inactive').slideUp();
			jQuery.post( ajaxurl, {
				'action': 'trx_addons_hide_activation_notice',
				'nonce': jQuery('.trx_addons_theme_panel_theme_inactive [name="trx_addons_nonce"]' ).val()
				}, function (response) {} );
			e.preventDefault();
			return false;
	});

	// Find activation button and reload page on click
	var $btn = jQuery('input[data-action="trx_addons_activation_restore"]') ,
		$action = $btn.attr('data-action');

	$btn.addClass('trx_addons_activation_restore').removeAttr('data-action');

	// Button with action
	jQuery('.trx_addons_activation_restore:not(.activation-inited)').addClass('activation-inited')
		.on('click', function(e) {
			jQuery.post(ajaxurl, {
				action: $action,
				nonce: jQuery('[name="trx_addons_nonce"]' ).val(),
			}).done(function(response) {
				var rez = {};
				if (response=='' || response==0) {
					rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
				} else {
					try {
						rez = JSON.parse(response);
					} catch (e) {
						rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
						console.log(response);
					}
				}
				location.reload();
			});
			e.preventDefault();
			return false;
		});

});