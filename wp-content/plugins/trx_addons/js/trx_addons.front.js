/**
 * Init scripts
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

/* global jQuery:false */
/* global TRX_ADDONS_STORAGE:false */

jQuery(document).ready(function() {

	"use strict";

	var vc_init_counter = 0;
	var vc_resize = false;
	var $body = jQuery( 'body' );
	var $document = jQuery( document );
	var $window  = jQuery( window );

	trx_addons_init_actions();
	
	// Show preloader
	jQuery(window).on('beforeunload', function() {
		if (jQuery.browser && !jQuery.browser.safari) jQuery('#page_preloader').css({display: 'block', opacity: 0}).animate({opacity:0.8}, 300);
	});
	
	
	// Init actions
	function trx_addons_init_actions() {
		if (TRX_ADDONS_STORAGE['vc_edit_mode'] > 0 && jQuery('.vc_empty-placeholder').length==0 && vc_init_counter++ < 30) {
			setTimeout(trx_addons_init_actions, 200);
			return;
		}
		
		// Hide preloader
		jQuery('#page_preloader').animate({opacity:0}, 800, function() {
									jQuery(this).css( {display: 'none'} );
									});
	
        // Check for Retina display
        trx_addons_set_cookie('trx_addons_is_retina', trx_addons_is_retina() ? 1 : 0, 365);
	
		// Add ready actions to the hidden elements actions
		jQuery(document).on('action.init_hidden_elements', trx_addons_ready_actions);
	
		// Add resize on VC action vc-full-width-row
		jQuery(document).on('vc-full-width-row', function(e, el) {
			jQuery(document).trigger('action.resize_vc_row_start', [el]);
			jQuery(document).trigger('action.resize_vc_row_end', [el]);
		});
		jQuery(document).on('action.resize_vc_row_end', function(e, el) {
			vc_resize = true;
			trx_addons_resize_actions();
		});
	
		// Resize handlers
		jQuery(window).resize(function() {
			if (!vc_resize) trx_addons_resize_actions();
		});
	
		// Scroll handlers
		jQuery(window).scroll(function() {
			trx_addons_scroll_actions();
		});
	
		// First call to init core actions
		trx_addons_ready_actions();
		setTimeout(function() {		// Wait for VC init
			if (!vc_resize) trx_addons_resize_actions();
			trx_addons_scroll_actions();
		}, 1);
		
		// Inject our code in the VC function wpb_prepare_tab_content()
		// to init our elements on the new VC tabs, tour and accordion activation
		typeof window.wpb_prepare_tab_content == "function"
			&& typeof window.wpb_prepare_tab_content_old == "undefined"
			&& (window.wpb_prepare_tab_content_old = window.wpb_prepare_tab_content)
			&& (window.wpb_prepare_tab_content = function(e, ui) {
				// Call ThemeREX Addons actions
				if (typeof ui.newPanel !== 'undefined' && ui.newPanel.length > 0) {
					jQuery(document).trigger('action.init_hidden_elements', [ui.newPanel]);
				} else if (typeof ui.panel !== 'undefined' && ui.panel.length > 0) {
					jQuery(document).trigger('action.init_hidden_elements', [ui.panel]);
				}
				// Call old VC handler
				window.wpb_prepare_tab_content_old(e, ui);
			});
		// Inject our code in the VC function vc_accordionActivate()
		// to init our elements on the old VC accordion activation
		typeof window.vc_accordionActivate == "function"
			&& typeof window.vc_accordionActivate_old == "undefined"
			&& (window.vc_accordionActivate_old = window.vc_accordionActivate)
			&& (window.vc_accordionActivate = function(e, ui) {
				// Call ThemeREX Addons actions
				if (typeof ui.newPanel !== 'undefined' && ui.newPanel.length > 0) {
					jQuery(document).trigger('action.init_hidden_elements', [ui.newPanel]);
				} else if (typeof ui.panel !== 'undefined' && ui.panel.length > 0) {
					jQuery(document).trigger('action.init_hidden_elements', [ui.panel]);
				}
				// Call old VC handler
				window.vc_accordionActivate_old(e, ui);
			});
	}
	
	
	
	// Page first load actions
	//==============================================
	function trx_addons_ready_actions(e, container) {
	
		if (container === undefined) container = jQuery('body');
	
		// Animate to the page-inner links
		//----------------------------------------------
		if (TRX_ADDONS_STORAGE['animate_inner_links'] > 0 && !container.hasClass('animate_to_inited')) {
			container.addClass('animate_to_inited')
				.on('click', 'a', function(e) {
					var link_obj = jQuery(this);
					var link_parent = link_obj.parent();
					if (link_parent.parent().hasClass('trx_addons_tabs_titles')	// trx_addons_tabs
						|| link_parent.hasClass('vc_tta-tab') 			// new VC tabs, old VC tabs, new VC tour
						|| link_obj.hasClass('ui-tabs-anchor') 			// old VC tour
						|| link_parent.hasClass('vc_tta-panel-title')	// new VC accordion
						|| link_parent.hasClass('wpb_accordion_header') // old VC accordion
						) return;
					var href = link_obj.attr('href');
					if (href == '#') return;
					if (trx_addons_is_local_link(href)) {
						var pos = href.indexOf('#');
						href = pos < 0 ? 0 : href.substr(pos);
						trx_addons_document_animate_to(href);
						e.preventDefault();
						return false;
					}
				});
		}
	
		// Tabs
		//------------------------------------
		if (container.find('.trx_addons_tabs:not(.inited)').length > 0 && jQuery.ui && jQuery.ui.tabs) {
			container.find('.trx_addons_tabs:not(.inited)').each(function () {
				// Get initially opened tab
				var init = jQuery(this).data('active');
				if (isNaN(init)) {
					init = 0;
					var active = jQuery(this).find('> ul > li[data-active="true"]').eq(0);
					if (active.length > 0) {
						init = active.index();
						if (isNaN(init) || init < 0) init = 0;
					}
				} else {
					init = Math.max(0, init);
				}
				// Get disabled tabs
				var disabled = [];
				jQuery(this).find('> ul > li[data-disabled="true"]').each(function() {
					disabled.push(jQuery(this).index());
				});
				// Init tabs
				jQuery(this).addClass('inited').tabs({
					active: init,
					disabled: disabled,
					show: {
						effect: 'fadeIn',
						duration: 300
					},
					hide: {
						effect: 'fadeOut',
						duration: 300
					},
					create: function( event, ui ) {
						if (ui.panel.length > 0) jQuery(document).trigger('action.init_hidden_elements', [ui.panel]);
					},
					activate: function( event, ui ) {
						if (ui.newPanel.length > 0) jQuery(document).trigger('action.init_hidden_elements', [ui.newPanel]);
					}
				});
			});
		}
	
	
		// Accordion
		//------------------------------------
		if (container.find('.trx_addons_accordion:not(.inited)').length > 0 && jQuery.ui && jQuery.ui.accordion) {
			container.find('.trx_addons_accordion:not(.inited)').each(function () {
				// Get headers selector
				var accordion = jQuery(this);
				var headers = accordion.data('headers');
				if (headers===undefined) headers = 'h5';
				// Get height style
				var height_style = accordion.data('height-style');
				if (height_style===undefined) height_style = 'content';
				// Get collapsible
				var collapsible = accordion.data('collapsible');
				if (collapsible===undefined) collapsible = false;
				// Get initially opened tab
				var init = accordion.data('active');
				var active = false;
				if (isNaN(init)) {
					init = 0;
					var active = accordion.find(headers+'[data-active="true"]').eq(0);
					if (active.length > 0) {
						while (!active.parent().hasClass('trx_addons_accordion')) {
							active = active.parent();
						}
						init = active.index();
						if (isNaN(init) || init < 0) init = 0;
					}
				} else {
					init = Math.max(0, init);
				}
				// Init accordion
				accordion.addClass('inited').accordion({
					active: init,
					collapsible: collapsible,
					header: headers,
					heightStyle: height_style,
					create: function( event, ui ) {
						if (ui.panel.length > 0) {
							jQuery(document).trigger('action.init_hidden_elements', [ui.panel]);
						} else if (active !== false && active.length > 0) {
							// If headers and panels wrapped into div
							active.find('>'+headers).trigger('click');
						}
					},
					activate: function( event, ui ) {
						if (ui.newPanel.length > 0) jQuery(document).trigger('action.init_hidden_elements', [ui.newPanel]);
					}
				});
			});
		}
	
	
		// Range Slider
		//------------------------------------
		if (container.find('.trx_addons_range_slider:not(.inited)').length > 0 && jQuery.ui && jQuery.ui.slider) {
			container.find('.trx_addons_range_slider:not(.inited)').each(function () {
				// Get parameters
				var range_slider = jQuery(this);
				var linked_field = range_slider.data('linked_field');
				if (linked_field===undefined) linked_field = range_slider.prev('input[type="hidden"]');
				else linked_field = jQuery('#'+linked_field);
				if (linked_field.length == 0) return;
				var range_slider_cur = range_slider.find('> .trx_addons_range_slider_label_cur');
				var range_slider_type = range_slider.data('range');
				if (range_slider_type===undefined) range_slider_type = 'min';
				var values = linked_field.val().split(',');
				var minimum = range_slider.data('min');
				if (minimum===undefined) minimum = 0;
				var maximum = range_slider.data('max');
				if (maximum===undefined) maximum = 0;
				var step = range_slider.data('step');
				if (step===undefined) step = 1;
				// Init range slider
				var init_obj = {
					range: range_slider_type,
					min: minimum,
					max: maximum,
					step: step,
					slide: function(event, ui) {
						var cur_values = range_slider_type === 'min' ? [ui.value] : ui.values;
						linked_field.val(cur_values.join(','));
						for (var i=0; i<cur_values.length; i++) {
							range_slider_cur.eq(i)
									.html(cur_values[i])
									.css('left', Math.max(0, Math.min(100, (cur_values[i]-minimum)*100/(maximum-minimum)))+'%');
						}
					},
					create: function(event, ui) {
						for (var i=0; i<values.length; i++) {
							range_slider_cur.eq(i)
									.html(values[i])
									.css('left', Math.max(0, Math.min(100, (values[i]-minimum)*100/(maximum-minimum)))+'%');
						}
					}
				};
				if (range_slider_type === true)
					init_obj.values = values;
				else
					init_obj.value = values[0];
				range_slider.addClass('inited').slider(init_obj);
			});
		}
	
	
		// Sliders
		//----------------------------------------------
		jQuery(document).trigger('action.init_sliders', [container]);
	
	
		// Shortcodes
		//----------------------------------------------
		jQuery(document).trigger('action.init_shortcodes', [container]);
	
	
		// Video player
		//----------------------------------------------
		if (container.find('.trx_addons_video_player.with_cover .video_hover:not(.inited)').length > 0) {
			container.find('.trx_addons_video_player.with_cover .video_hover:not(.inited)')
				.addClass('inited')
				.on('click', function(e) {
					
					// If video in the popup
					if (jQuery(this).hasClass('trx_addons_popup_link')) return true;
					
					jQuery(this).parents('.trx_addons_video_player')
						.addClass('video_play')
						.find('.video_embed').html(jQuery(this).data('video'));
	
					// If video in the slide
					var slider = jQuery(this).parents('.slider_swiper');
					if (slider.length > 0) {
						var id = slider.attr('id');
						TRX_ADDONS_STORAGE['swipers'][id].stopAutoplay();
						// If slider have controller - stop it too
						id = slider.data('controller');
						if (id && TRX_ADDONS_STORAGE['swipers'][id+'_swiper'])
							TRX_ADDONS_STORAGE['swipers'][id+'_swiper'].stopAutoplay();
						
					}
	
					jQuery(window).trigger('resize');
					e.preventDefault();
					return false;
				});
		}
	
	
		// Popups
		//----------------------------------------------

		// PrettyPhoto Engine
		if (TRX_ADDONS_STORAGE['popup_engine'] == 'pretty') {
			// Display lightbox on click on the image
			container.find("a[href$='jpg']:not(.inited),a[href$='jpeg']:not(.inited),a[href$='png']:not(.inited),a[href$='gif']:not(.inited)").each(function() {
				if (!jQuery(this).parent().hasClass('woocommerce-product-gallery__image'))
					jQuery(this).attr('rel', 'prettyPhoto[slideshow]');
			});
			var images = container.find("a[rel*='prettyPhoto']:not(.inited):not(.esgbox):not([data-rel*='pretty']):not([rel*='magnific']):not([data-rel*='magnific'])").addClass('inited');
			try {
				images.prettyPhoto({
					social_tools: '',
					theme: 'facebook',
					deeplinking: false
				});
			} catch (e) {};
		
		// or Magnific Popup Engine
		} else if (TRX_ADDONS_STORAGE['popup_engine']=='magnific') {
			// Display lightbox on click on the image
			container.find("a[href$='jpg']:not(.inited),a[href$='jpeg']:not(.inited),a[href$='png']:not(.inited),a[href$='gif']:not(.inited)").each(function() {
				if (!jQuery(this).parent().hasClass('woocommerce-product-gallery__image'))
					jQuery(this).attr('rel', 'magnific');
			});
			var images = container.find("a[rel*='magnific']:not(.inited):not(.esgbox):not(.prettyphoto):not([rel*='pretty']):not([data-rel*='pretty'])").addClass('inited');
			try {
				images.magnificPopup({
					type: 'image',
					mainClass: 'mfp-img-mobile',
					closeOnContentClick: true,
					closeBtnInside: true,
					fixedContentPos: true,
					midClick: true,
					//removalDelay: 500, 
					preloader: true,
					tLoading: TRX_ADDONS_STORAGE['msg_magnific_loading'],
					gallery:{
						enabled: true
					},
					image: {
						tError: TRX_ADDONS_STORAGE['msg_magnific_error'],
						verticalFit: true
					},
					zoom: {
						enabled: true,
						duration: 300,
						easing: 'ease-in-out',
						opener: function(openerElement) {
							// openerElement is the element on which popup was initialized, in this case its <a> tag
							// you don't need to add "opener" option if this code matches your needs, it's defailt one.
							if (!openerElement.is('img')) {
								if (openerElement.parents('.trx_addons_hover').find('img').length > 0)
									openerElement = openerElement.parents('.trx_addons_hover').find('img');
								else if (openerElement.find('img').length > 0)
									 openerElement = openerElement.find('img');
								else if (openerElement.siblings('img').length > 0)
									 openerElement = openerElement.siblings('img');
								else if (openerElement.parent().parent().find('img').length > 0)
									 openerElement = openerElement.parent().parent().find('img');
							}
							return openerElement; 
						}
					},
					callbacks: {
						beforeClose: function(){
							jQuery('.mfp-figure figcaption').hide();
							jQuery('.mfp-figure .mfp-arrow').hide();
						}
					}
				});
			} catch (e) {};

			// Prepare links to popups & panels
			//----------------------------------------
			var on_leaving_site = [],
				in_page_edit_mode = $body.hasClass('elementor-editor-active')
					|| $body.hasClass('wp-admin')
					|| $body.hasClass('block-editor-page');
			// Init popups and panels links
			container.find('.sc_layouts_popup:not(.inited),.sc_layouts_panel:not(.inited)').each( function() {
				var $self = jQuery(this),
					id = $self.attr('id'),
					show = false;
				if (!id) return;
				var is_panel = $self.hasClass('sc_layouts_panel'),
					link = jQuery('a[href="#'+id+'"]');
				if (link.length === 0) {
					$body.append('<a href="#'+id+'" class="trx_addons_hidden"></a>');
					link = jQuery('a[href="#'+id+'"]');
				}
				if ($self.hasClass('sc_layouts_show_on_page_load')) {
					show = true;
				} else if ($self.hasClass('sc_layouts_show_on_page_load_once') && trx_addons_get_cookie('trx_addons_show_on_page_load_once_'+id) != '1') {
					trx_addons_set_cookie('trx_addons_show_on_page_load_once_'+id, '1');
					show = true;
				} else if ($self.hasClass('sc_layouts_show_on_page_close') && trx_addons_get_cookie('trx_addons_show_on_page_close_'+id) != '1') {
					on_leaving_site.push({
						link: link,
						id: id
					});
				}
				if (show) {
					// Display popups (panels) on the page (site) load
					if ( ! in_page_edit_mode ) {
						setTimeout( function() {
							link.trigger('click');
						}, $self.data('delay') > 0 ? $self.data('delay') * 1000 : 0 );
					}
				}
				link
					.addClass(is_panel ? 'trx_addons_panel_link' : 'trx_addons_popup_link')
					.data('panel', $self);
				$self
					.addClass('inited')
					.on('click', '.sc_layouts_panel_close', function(e) {
						trx_addons_close_panel($self);
						e.preventDefault();
						return false;
					});
			});

			// Display popup when user leaving site
			if ( on_leaving_site.length > 0 && ! in_page_edit_mode ) {
				var showed = false;
				$window.on( 'mousemove', function(e) {
					if ( showed ) return;
					var y = typeof e.clientY != 'undefined' ? e.clientY : 999;
					if ( y < _adminbar_height + 15 ) {
						showed = true;
						on_leaving_site.forEach( function(item) {
							item.link.trigger('click');
							trx_addons_set_cookie('trx_addons_show_on_page_close_'+item.id, '1');
						});
					}
				} );
			}
			// Close panel
			window.trx_addons_close_panel = function(panel) {
				if ( panel.hasClass('sc_layouts_panel') ) {
					$document.trigger('action.close_popup_elements', [panel]);
				}
				setTimeout( function() {
					panel.removeClass('sc_layouts_panel_opened');
					if (panel.prev().hasClass('sc_layouts_panel_hide_content')) {
						panel.prev().removeClass('sc_layouts_panel_opened');
					}
					$body.removeClass('sc_layouts_panel_opened sc_layouts_panel_opened_left sc_layouts_panel_opened_right sc_layouts_panel_opened_top sc_layouts_panel_opened_bottom');
					var panel_class = panel.data('panel-class');
					if ( panel_class ) {
						$body.removeClass( panel_class + '_opened' );
					}
					if (panel.data('popup-content') !== undefined) {
						setTimeout(function() { panel.empty(); }, 500);
					}
				}, trx_addons_apply_filters('trx_addons_filter_close_panel_timeout', panel.hasClass('sc_layouts_panel') && panel.data('animation-delay') !== undefined ? panel.data('animation-delay') : 0, panel) );
			};
			// Display lightbox on click on the popup link
			container.find( trx_addons_apply_filters( 'mfp-init-popup-selector', ".trx_addons_popup_link:not(.popup_inited)" ) )
				.addClass('popup_inited')
				.magnificPopup( trx_addons_apply_filters( 'mfp-init-popup-params', {
					type: 'inline',
					focus: 'input',
					removalDelay: trx_addons_apply_filters('trx_addons_filter_close_popup_timeout', 0),
					tLoading: TRX_ADDONS_STORAGE['msg_magnific_loading'],
					tClose: TRX_ADDONS_STORAGE['msg_magnific_close'],
					closeBtnInside: true,
					//closeMarkup: '<button title="%title%" type="button" class="mfp-close"><span class="mfp-close-icon">&#215;</span></button>',
					callbacks: {
						// Will fire when this before popup is opened
						// this - is Magnific Popup object
						beforeAppend: function () {
							var $mfp = this;
							// Prepare content for the popup
							$document.trigger('action.prepare_popup_elements', [$mfp.content, $mfp]);
						},
						beforeOpen: function() {
							var $mfp = this;
							$document.trigger('action.open_popup_elements', [$mfp.content]);
						},
						// Will fire when this exact popup is opened
						// this - is Magnific Popup object
						open: function () {
							// Get saved content or store it (if first open occured)
							trx_addons_prepare_popup_content(this.content, true);
						},
						close: function () {
							var $mfp = this;
							// Save and remove content before closing
							// if its contain video, audio or iframe
							trx_addons_close_panel($mfp.content);
						},
						// resize event triggers only when height is changed or layout forced
						resize: function () {
							var $mfp = this;
							trx_addons_resize_actions(jQuery($mfp.content));
						}
					}
				} ) );

			// Get saved content for panel or popup or store it (if first open occured)
			window.trx_addons_prepare_popup_content = function(container, autoplay) {
				var wrapper = jQuery(container);
				// Store popup content to the data-param or restore it when popup open again (second time)
				// if popup contains audio or video or iframe
				if (wrapper.data('popup-content') === undefined) {
					var html = wrapper.html();
					if (html.search(/\<(audio|video|iframe)/i) >= 0) {
						wrapper.data('popup-content', html);
					}
				} else {
					wrapper.html(wrapper.data('popup-content'));
					// Remove class 'inited' to reinit elements
					wrapper.find('.inited').removeClass('inited');
				}
				// Replace src with data-src
				wrapper.find('[data-src]').each(function() {
					jQuery(this).attr( 'src', jQuery(this).data('src') );
				});
				// Init hidden elements
				$document.trigger( 'action.init_hidden_elements', [wrapper] );
				// Init third-party plugins in the popup
				$document.trigger( 'action.init_popup_elements', [wrapper] );
				// If popup contain embedded video - add autoplay
				if (autoplay) trx_addons_set_autoplay(wrapper);
				// If popup contain essential grid
				var frame = wrapper.find('.esg-grid');
				if (frame.length > 0) {
					var wrappers = [".esg-tc.eec", ".esg-lc.eec", ".esg-rc.eec", ".esg-cc.eec", ".esg-bc.eec"];
					for (var i=0; i<wrappers.length; i++) {
						frame.find(wrappers[i]+'>'+wrappers[i]).unwrap();
					}
				}
				// Call resize actions for the new content
				$window.trigger('resize');
			};
		}
	
	
		// Likes counter
		//---------------------------------------------
		if (container.find('.post_counters_likes:not(.inited),.comment_counters_likes:not(.inited)').length > 0) {
			container.find('.post_counters_likes:not(.inited),.comment_counters_likes:not(.inited)')
				.addClass('inited')
				.on('click', function(e) {
					var button = jQuery(this);
					var inc = button.hasClass('enabled') ? 1 : -1;
					var post_id = button.hasClass('post_counters_likes') ? button.data('postid') :  button.data('commentid');
					var cookie_likes = trx_addons_get_cookie(button.hasClass('post_counters_likes') ? 'trx_addons_likes' : 'trx_addons_comment_likes');
					if (cookie_likes === undefined || cookie_likes===null) cookie_likes = '';
					jQuery.post(TRX_ADDONS_STORAGE['ajax_url'], {
						action: button.hasClass('post_counters_likes') ? 'post_counter' : 'comment_counter',
						nonce: TRX_ADDONS_STORAGE['ajax_nonce'],
						post_id: post_id,
						likes: inc
					}).done(function(response) {
						var rez = {};
						try {
							rez = JSON.parse(response);
						} catch (e) {
							rez = { error: TRX_ADDONS_STORAGE['msg_ajax_error'] };
							console.log(response);
						}
						if (rez.error === '') {
							var counter = rez.counter;
							if (inc == 1) {
								var title = button.data('title-dislike');
								button.removeClass('enabled trx_addons_icon-heart-empty').addClass('disabled trx_addons_icon-heart');
								cookie_likes += (cookie_likes.substr(-1)!=',' ? ',' : '') + post_id + ',';
							} else {
								var title = button.data('title-like');
								button.removeClass('disabled trx_addons_icon-heart').addClass('enabled trx_addons_icon-heart-empty');
								cookie_likes = cookie_likes.replace(','+post_id+',', ',');
							}
							button.data('likes', counter).attr('title', title).find(button.hasClass('post_counters_likes') ? '.post_counters_number' : '.comment_counters_number').html(counter);
							trx_addons_set_cookie(button.hasClass('post_counters_likes') ? 'trx_addons_likes' : 'trx_addons_comment_likes', cookie_likes, 365);
						} else {
							alert(TRX_ADDONS_STORAGE['msg_error_like']);
						}
					});
					e.preventDefault();
					return false;
				});
		}
	
	
		// Socials share
		//----------------------------------------------
		if (container.find('.socials_share .socials_caption:not(.inited)').length > 0) {
			container.find('.socials_share .socials_caption:not(.inited)').each(function() {
				jQuery(this).addClass('inited').on('click', function(e) {
					jQuery(this).siblings('.social_items').slideToggle();	//.toggleClass('opened');
					e.preventDefault();
					return false;
				});
			});
		}
		if (container.find('.socials_share .social_items:not(.inited)').length > 0) {
			container.find('.socials_share .social_items:not(.inited)').each(function() {
				jQuery(this).addClass('inited').on('click', '.social_item_popup', function(e) {
					var url = jQuery(this).data('link');
					window.open(url, '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=480, height=400, toolbar=0, status=0');
					e.preventDefault();
					return false;
				});
			});
		}
		
		
		// Widgets decoration
		//----------------------------------------------
	
		// Decorate nested lists in widgets and side panels
		container.find('.widget ul > li').each(function() {
			if (jQuery(this).find('ul').length > 0) {
				jQuery(this).addClass('has_children');
			}
		});
	
		// Archive widget decoration
		container.find('.widget_archive a:not(.inited)').addClass('inited').each(function() {
			var val = jQuery(this).html().split(' ');
			if (val.length > 1) {
				val[val.length-1] = '<span>' + val[val.length-1] + '</span>';
				jQuery(this).html(val.join(' '))
			}
		});
	
	
		// Menu
		//----------------------------------------------
	
		// Prepare menus (if menu cache is used)
		jQuery('.sc_layouts_menu_nav').each(function() {
			if (jQuery(this).find('.current-menu-item').length == 0 || jQuery('body').hasClass('blog_template')) {
				if (TRX_ADDONS_STORAGE['menu_cache'] === undefined) TRX_ADDONS_STORAGE['menu_cache'] = [];
				var id = jQuery(this).attr('id');
				if (id === undefined) {
					id = ('sc_layouts_menu_nav_' + Math.random()).replace('.', '');
					jQuery(this).attr('id', id);
				}
				TRX_ADDONS_STORAGE['menu_cache'].push('#'+id);
			}
		});
		if (TRX_ADDONS_STORAGE['menu_cache'] && TRX_ADDONS_STORAGE['menu_cache'].length > 0) {
			// Mark the current menu item and its parent items in the cached menus
			var href = window.location.href;
			for (var menu in TRX_ADDONS_STORAGE['menu_cache']) {
				menu = jQuery(TRX_ADDONS_STORAGE['menu_cache'][menu]+':not(.prepared)');
				if (menu.length==0) continue;
				menu.addClass('prepared');
				menu.find('li').removeClass('current-menu-ancestor current-menu-parent current-menu-item current_page_item');
				menu.find('a[href="'+href+'"]').each(function(idx) {
					var li = jQuery(this).parent();
					li.addClass('current-menu-item');
					if (li.hasClass('menu-item-object-page')) li.addClass('current_page_item');
					var cnt = 0;
					while ((li = li.parents('li')).length > 0) {
						cnt++;
						li.addClass('current-menu-ancestor'+(cnt==1 ? ' current-menu-parent' : ''));
					}
				});
			}
		}
	
	
		// Other settings
		//------------------------------------
	
		// Scroll to top button
		container.find('.trx_addons_scroll_to_top:not(.inited)').addClass('inited').on('click', function(e) {
			jQuery('html,body').animate({
				scrollTop: 0
			}, 'slow');
			e.preventDefault();
			return false;
		});
	
		// Call plugins specific action (if exists)
		//----------------------------------------------
		jQuery(document).trigger('action.before_ready_trx_addons');
		jQuery(document).trigger('action.ready_trx_addons');
		jQuery(document).trigger('action.after_ready_trx_addons');
		
	} //end ready
	

	// Increment post views counter via AJAX
	if (TRX_ADDONS_STORAGE['ajax_views']) {
		jQuery(document).on('action.ready_trx_addons', function() {
			if (!TRX_ADDONS_STORAGE['post_views_counter_inited']) {
				TRX_ADDONS_STORAGE['post_views_counter_inited'] = true;
				setTimeout(function() {
					jQuery.post(TRX_ADDONS_STORAGE['ajax_url'], {
						action: 'post_counter',
						nonce: TRX_ADDONS_STORAGE['ajax_nonce'],
						post_id: TRX_ADDONS_STORAGE['post_id'],
						views: 1
					}).done(function(response) {
						var rez = {};
						try {
							rez = JSON.parse(response);
						} catch (e) {
							rez = { error: TRX_ADDONS_STORAGE['ajax_error'] };
							console.log(response);
						}
						if (rez.error === '') {
							jQuery('.post_counters_single .post_counters_views .post_counters_number,.sc_layouts_title_meta .post_counters_views .post_counters_number').html(rez.counter);
						}
					});
				}, 10);
			}
		});
	}


	
	// Scroll actions
	//==============================================
	
	// Do actions when page scrolled
	function trx_addons_scroll_actions() {

		var scroll_offset = jQuery(window).scrollTop();
		var scroll_to_top_button = jQuery('.trx_addons_scroll_to_top');
		var adminbar_height = Math.max(0, jQuery('#wpadminbar').height());
	
		// Scroll to top button show/hide
		if (scroll_to_top_button.length > 0) {
			if (scroll_offset > 100)
				scroll_to_top_button.addClass('show');
			else
				scroll_to_top_button.removeClass('show');
		}
	
		// Scroll actions for animated elements
		jQuery('[data-animation^="animated"]:not(.animated)').each(function() {
			if (jQuery(this).offset().top < scroll_offset + jQuery(window).height())
				jQuery(this).addClass(jQuery(this).data('animation'));
		});
	
		// Call theme/plugins specific action (if exists)
		//----------------------------------------------
		jQuery(document).trigger('action.scroll_trx_addons');
	}
	
	
	
	// Resize actions
	//==============================================
	
	// Do actions when page scrolled
	function trx_addons_resize_actions(cont) {
		trx_addons_resize_video(cont);
	
		// Call theme/plugins specific action (if exists)
		//----------------------------------------------
		jQuery(document).trigger('action.resize_trx_addons', [cont]);
	}
	
	
	
	// Fit video frames to document width
	function trx_addons_resize_video(cont) {
		if (cont===undefined) cont = jQuery('body');
		cont.find('video').each(function() {
			// If item now invisible
			if (jQuery(this).parents('div:hidden,section:hidden,article:hidden').length > 0) {
				return;
			}
			var video = jQuery(this).eq(0);
			var ratio = (video.data('ratio')!=undefined ? video.data('ratio').split(':') : [16,9]);
			ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
			var mejs_cont = video.parents('.mejs-video');
			var w_attr = video.data('width');
			var h_attr = video.data('height');
			if (!w_attr || !h_attr) {
				w_attr = video.attr('width');
				h_attr = video.attr('height');
				if (!w_attr || !h_attr) return;
				video.data({'width': w_attr, 'height': h_attr});
			}
			var percent = (''+w_attr).substr(-1)=='%';
			w_attr = parseInt(w_attr);
			h_attr = parseInt(h_attr);
			var w_real = Math.round(mejs_cont.length > 0 ? Math.min(percent ? 10000 : w_attr, mejs_cont.parents('div,article').width()) : video.width()),
				h_real = Math.round(percent ? w_real/ratio : w_real/w_attr*h_attr);
			if (parseInt(video.attr('data-last-width'))==w_real) return;
			if (mejs_cont.length > 0 && mejs) {
				trx_addons_set_mejs_player_dimensions(video, w_real, h_real);
			}
			if (percent) {
				video.height(h_real);
			} else {
				video.attr({'width': w_real, 'height': h_real}).css({'width': w_real+'px', 'height': h_real+'px'});
			}
			video.attr('data-last-width', w_real);
		});
		cont.find('.video_frame iframe').each(function() {
			// If item now invisible
			if (jQuery(this).parents('div:hidden,section:hidden,article:hidden').length > 0) {
				return;
			}
			var iframe = jQuery(this).eq(0);
			if (iframe.attr('src').indexOf('soundcloud')>0) return;
			var ratio = (iframe.data('ratio')!=undefined 
							? iframe.data('ratio').split(':') 
							: (iframe.parent().data('ratio')!=undefined 
								? iframe.parent().data('ratio').split(':') 
								: (iframe.find('[data-ratio]').length>0 
									? iframe.find('[data-ratio]').data('ratio').split(':') 
									: [16,9]
									)
								)
							);
			ratio = ratio.length!=2 || ratio[0]==0 || ratio[1]==0 ? 16/9 : ratio[0]/ratio[1];
			var w_attr = iframe.attr('width');
			var h_attr = iframe.attr('height');
			if (!w_attr || !h_attr) {
				return;
			}
			var percent = (''+w_attr).substr(-1)=='%';
			w_attr = parseInt(w_attr);
			h_attr = parseInt(h_attr);
			var pw = iframe.parent().width(),
				ph = iframe.parent().height(),
				w_real = pw,
				h_real = Math.round(percent ? w_real/ratio : w_real/w_attr*h_attr);
			if (iframe.parent().css('position') == 'absolute' && h_real > ph) {
				h_real = ph;
				w_real = Math.round(percent ? h_real*ratio : h_real*w_attr/h_attr)
			}
			if (parseInt(iframe.attr('data-last-width'))==w_real) return;
			iframe.css({'width': w_real+'px', 'height': h_real+'px'});
			iframe.attr('data-last-width', w_real);
		});
	}
	
	
	// Set Media Elements player dimensions
	function trx_addons_set_mejs_player_dimensions(video, w, h) {
		if (mejs) {
			for (var pl in mejs.players) {
				if (mejs.players[pl].media.src == video.attr('src')) {
					if (mejs.players[pl].media.setVideoSize) {
						mejs.players[pl].media.setVideoSize(w, h);
					}
					mejs.players[pl].setPlayerSize(w, h);
					mejs.players[pl].setControlsSize();
				}
			}
		}
	}

});