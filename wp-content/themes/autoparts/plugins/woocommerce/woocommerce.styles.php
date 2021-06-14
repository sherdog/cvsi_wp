<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( !function_exists( 'autoparts_woocommerce_get_css' ) ) {
	add_filter( 'autoparts_filter_get_css', 'autoparts_woocommerce_get_css', 10, 4 );
	function autoparts_woocommerce_get_css($css, $colors, $fonts, $scheme='') {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

.woocommerce .checkout table.shop_table .product-name .variation,
.woocommerce .shop_table.order_details td.product-name .variation {
	{$fonts['p_font-family']}
}
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce ul.products li.product .post_header, .woocommerce-page ul.products li.product .post_header,
.single-product div.product .woocommerce-tabs .wc-tabs li a,
.woocommerce .shop_table th,
.woocommerce span.onsale,
.woocommerce nav.woocommerce-pagination ul li a,
.woocommerce nav.woocommerce-pagination ul li span.current,
.woocommerce div.product p.price, .woocommerce div.product span.price,
.woocommerce div.product .summary .stock,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta strong,
.woocommerce-page #reviews #comments ol.commentlist li .comment-text p.meta strong,
.woocommerce table.cart td.product-name a, .woocommerce-page table.cart td.product-name a, 
.woocommerce #content table.cart td.product-name a, .woocommerce-page #content table.cart td.product-name a,
.woocommerce .checkout table.shop_table .product-name,
.woocommerce .shop_table.order_details td.product-name,
.woocommerce .order_details li strong,
.woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-content .woocommerce-Address-title a {
	{$fonts['h5_font-family']}
}
#btn-buy,
.woocommerce ul.products li.product .button, .woocommerce div.product form.cart .button,
.woocommerce .woocommerce-message .button,
.woocommerce #review_form #respond p.form-submit input[type="submit"],
.woocommerce-page #review_form #respond p.form-submit input[type="submit"],
.woocommerce table.my_account_orders .order-actions .button,
.woocommerce .button, .woocommerce-page .button,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button
.woocommerce #respond input#submit,
.woocommerce input[type="button"], .woocommerce-page input[type="button"],
.woocommerce input[type="submit"], .woocommerce-page input[type="submit"] {
	{$fonts['button_font-family']}
	{$fonts['button_font-size']}
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
.woocommerce ul.products li.product .post_header .post_tags,
.woocommerce div.product .product_meta span > a, .woocommerce div.product .product_meta span > span,
.woocommerce div.product form.cart .reset_variations,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta time, .woocommerce-page #reviews #comments ol.commentlist li .comment-text p.meta time {
	{$fonts['info_font-family']}
}

CSS;
		
			
			$rad = autoparts_get_border_radius();
			$css['fonts'] .= <<<CSS

CSS;
		}


		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Page header */
.woocommerce .woocommerce-breadcrumb {
	color: {$colors['text']};
}
.woocommerce .woocommerce-breadcrumb a {
	color: {$colors['text_link']};
}
.woocommerce .woocommerce-breadcrumb a:hover {
	color: {$colors['text_hover']};
}
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
.woof .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
.woof .widget_price_filter .ui-slider .ui-slider-handle {
	background-color: {$colors['text_hover']};
}

/* List and Single product */
.woocommerce .woocommerce-ordering select {
	border-color: {$colors['bd_color']};
	background-color: {$colors['inverse_light']};
}
.woocommerce span.onsale {
	color: {$colors['inverse_link']};
	background-color: {$colors['text_link']};
}

.woocommerce.columns-3 ul.products li.product:after, .woocommerce-page.columns-3 ul.products li.product:after,
.woocommerce .related.products ul.products li.product.column-1_3:after, .woocommerce-page .related.products ul.products li.product.column-1_3:after,
.woocommerce .upcells.products ul.products li.product.column-1_3:after, .woocommerce-page .upcells.products ul.products li.product.column-1_3:after,
.woocommerce ul.products li.product.column-1_3:after, .woocommerce-page ul.products li.product.column-1_3:after,
.woocommerce.columns-3 ul.products li.product:nth-child(2n+1):after, .woocommerce-page.columns-3 ul.products li.product:nth-child(2n+1):after,
.woocommerce .related.products ul.products li.product.column-1_3:nth-child(2n+1):after, .woocommerce-page .related.products ul.products li.product.column-1_3:nth-child(2n+1):after,
.woocommerce .upcells.products ul.products li.product.column-1_3:nth-child(2n+1):after, .woocommerce-page .upcells.products ul.products li.product.column-1_3:nth-child(2n+1):after,
.woocommerce ul.products li.product.column-1_3:nth-child(2n+1):after, .woocommerce-page ul.products li.product.column-1_3:nth-child(2n+1):after{
	border-color: {$colors['bg_color']};
}
@media(max-width: 1023px) {
    .woocommerce.columns-3 ul.products li.product:after,
	.woocommerce-page.columns-3 ul.products li.product:after,
	.woocommerce .related.products ul.products li.product.column-1_3:after,
	.woocommerce-page .related.products ul.products li.product.column-1_3:after,
	.woocommerce .upcells.products ul.products li.product.column-1_3:after,
	.woocommerce-page .upcells.products ul.products li.product.column-1_3:after,
	.woocommerce ul.products li.product.column-1_3:after,
	.woocommerce-page ul.products li.product.column-1_3:after {
	    border-color: {$colors['bg_color']};
	}
}

.woocommerce .shop_mode_thumbs ul.products li.product .post_item, .woocommerce-page .shop_mode_thumbs ul.products li.product .post_item,
.woocommerce .facetwp-template ul.products li.product .post_item{
	background-color: {$colors['alter_bg_color']};
}
.woocommerce .shop_mode_thumbs ul.products li.product .post_item:hover, .woocommerce-page .shop_mode_thumbs ul.products li.product .post_item:hover {
	background-color: {$colors['alter_bg_hover']};
}

.woocommerce .shop_mode_list ul.products li.product .post_item, .woocommerce-page .shop_mode_list ul.products li.product .post_item, 
.woocommerce .shop_mode_list ul.products li.product .post_item:hover, .woocommerce-page .shop_mode_list ul.products li.product .post_item:hover {
	background-color: transparent;
}

.woocommerce .shop_mode_list ul.products li.product .post_item .post_featured, .woocommerce-page .shop_mode_list ul.products li.product .post_item .post_featured{
	background-color: {$colors['alter_bg_color']};
} 

.woocommerce ul.products li.product .post_header a {
	color: {$colors['text_link']};
}
.woocommerce ul.products li.product .post_header a:hover {
	color: {$colors['text_hover']};
}
.woocommerce ul.products li.product .post_header .post_tags,
.woocommerce ul.products li.product .post_header .post_tags a {
	color: {$colors['text_link']};
}
.woocommerce ul.products li.product .post_header .post_tags a:hover {
	color: {$colors['text_hover']};
}
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce ul.products li.product .price ins, .woocommerce-page ul.products li.product .price ins {
	color: {$colors['text_link']};
}
.woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del {
	color: {$colors['text_hover']};
}

.woocommerce .woocommerce-pagination ul.page-numbers li a, .woocommerce-page .woocommerce-pagination ul.page-numbers li a{
	color: {$colors['text']};
	background-color: {$colors['inverse_text']};
}

.woocommerce div.product p.price, .woocommerce div.product span.price,
.woocommerce span.amount, .woocommerce-page span.amount {
	color: {$colors['extra_text']};
}
aside.woocommerce div.product p.price, aside.woocommerce div.product span.price,
aside.woocommerce span.amount, aside.woocommerce-page span.amount,
aside.woocommerce.widget_shopping_cart .quantity{
	color: {$colors['text_light']};
}
.woocommerce.single-product div.product p.price, .woocommerce.single-product div.product span.price,
.woocommerce.single-product span.amount, .woocommerce-page.single-product span.amount {
	color: {$colors['extra_text']};
}

.woocommerce table.shop_table td span.amount {
	color: {$colors['text']};
}

.woocommerce table.shop_table tr.order-total td span.amount {
	color: {$colors['text_hover']};
}

aside.woocommerce del,
.woocommerce del, .woocommerce del > span.amount, 
.woocommerce-page del, .woocommerce-page del > span.amount {
	color: {$colors['text_light']} !important;
}
.woocommerce .price del:before {
	background-color: {$colors['text_light']};
}
.woocommerce div.product form.cart div.quantity span, .woocommerce-page div.product form.cart div.quantity span,
.woocommerce .shop_table.cart div.quantity span, .woocommerce-page .shop_table.cart div.quantity span {
	color: {$colors['text_link']};
}
.woocommerce div.product form.cart div.quantity span:hover, .woocommerce-page div.product form.cart div.quantity span:hover,
.woocommerce .shop_table.cart div.quantity span:hover, .woocommerce-page .shop_table.cart div.quantity span:hover {
	color: {$colors['text_hover']};
}

.woocommerce div.product .product_meta span > a,
.woocommerce div.product .product_meta span > span {
	color: {$colors['text_link']};
}
.woocommerce div.product .product_meta a:hover {
	color: {$colors['text_hover']};
}

.woocommerce div.product div.images .flex-viewport,
.woocommerce div.product div.images img {
	background-color: {$colors['inverse_text']};
}
.woocommerce div.product div.images a:hover img {
	border-color: {$colors['text_link']};
}

.woocommerce div.product .woocommerce-tabs .panel, .woocommerce #content div.product .woocommerce-tabs .panel, .
woocommerce-page div.product .woocommerce-tabs .panel, .woocommerce-page #content div.product .woocommerce-tabs .panel {
	border-color: {$colors['bd_color']};
}
.single-product div.product .woocommerce-tabs .wc-tabs li a {
	color: {$colors['text_light']};
}
.single-product div.product .woocommerce-tabs .wc-tabs li.active a {
	color: {$colors['text_link']};
}
.single-product div.product .woocommerce-tabs .wc-tabs li:not(.active) a:hover {
	color: {$colors['text_link']};
}
.single-product div.product .woocommerce-tabs {
	background-color: {$colors['inverse_text']};
}
.single-product div.product .woocommerce-tabs .panel {
	color: {$colors['text']};
}
.woocommerce table.shop_attributes th {
	color: {$colors['text']};
}


/* Related Products */
.related .post_item{
	background-color: {$colors['inverse_text']};
}
.single-product ul.products li.product .post_data {
	color: {$colors['text']};
	background-color: {$colors['inverse_text']};
}
.single-product ul.products li.product .post_data .price span.amount {
	color: {$colors['text']};
}
.single-product ul.products li.product .post_data .post_header .post_tags,
.single-product ul.products li.product .post_data .post_header .post_tags a,
.single-product ul.products li.product .post_data a {
	color: {$colors['text_link']};
}
.single-product ul.products li.product .post_data .post_header .post_tags a:hover,
.single-product ul.products li.product .post_data a:hover {
	color: {$colors['text_hover']};
}
.single-product ul.products li.product .post_data .button {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.single-product ul.products li.product .post_data .button:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}

.woocommerce .related ul.products li.product .price, .woocommerce-page .related ul.products li.product .price, 
.woocommerce .related ul.products li.product .price ins, .scheme_default .woocommerce-page .related ul.products li.product .price ins{
	color: {$colors['text']};
}

/* Rating */
.woocommerce .star-rating span{
	color: {$colors['bd_color']};
}
.woocommerce .star-rating span:before {
	color: {$colors['extra_link']};
}
#review_form #respond p.form-submit input[type="submit"] {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
#review_form #respond p.form-submit input[type="submit"]:hover,
#review_form #respond p.form-submit input[type="submit"]:focus {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_hover']};
}
.woocommerce .star-rating:before, .woocommerce-page .star-rating:before, .woocommerce p.stars a:before, .woocommerce p.stars a:hover ~ a:before, .woocommerce p.stars.selected a.active ~ a:before {
    color: {$colors['bd_color']};
}
.woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a, .woocommerce .woocommerce-info:before, 
.woocommerce p.stars.selected a:not(.active):before, .woocommerce p.stars.selected a.active:before, .woocommerce p.stars:hover a:before {
	color: {$colors['extra_link']};
}

/* Buttons */
.autoparts_shop_mode_buttons a {
	color: {$colors['text_link']};
}
.autoparts_shop_mode_buttons a:hover {
	color: {$colors['text_hover']};
}

.woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, 
.woocommerce #respond input#submit[disabled]:disabled, .woocommerce a.button.disabled, 
.woocommerce a.button:disabled, .woocommerce a.button[disabled]:disabled, .woocommerce button.button.disabled, 
.woocommerce button.button:disabled, .woocommerce button.button[disabled]:disabled, 
.woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button[disabled]:disabled {
	color: {$colors['inverse_text']};
}

.woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled:hover, 
.woocommerce #respond input#submit[disabled]:disabled:hover, .woocommerce a.button.disabled:hover, 
.woocommerce a.button:disabled:hover, .woocommerce a.button[disabled]:disabled:hover, .woocommerce button.button.disabled:hover, 
.woocommerce button.button:disabled:hover, .woocommerce button.button[disabled]:disabled:hover, 
.woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button[disabled]:disabled:hover {
	background-color: {$colors['text_hover']};
}

.woocommerce .widget_shopping_cart .button.checkout {
	background-color: {$colors['text_link']};
}
.woocommerce .widget_shopping_cart .button.checkout:hover {
	background-color: {$colors['text_hover']};
}

.woocommerce .actions .button {
	background-color: {$colors['text_hover']};
}
.woocommerce .actions .button:hover {
	background-color: {$colors['text_link']};
}
.woocommerce .actions .coupon .button {
	background-color: {$colors['text_link']};
}
.woocommerce .actions .coupon .button:hover {
	background-color: {$colors['text_hover']};
}



/* Messages */
.woocommerce .woocommerce-message,
.woocommerce .woocommerce-info {
	background-color: {$colors['alter_bg_color']};
	border-top-color: {$colors['bd_color']};
}
.woocommerce .woocommerce-error {
	background-color: {$colors['alter_bg_color']};
	border-top-color: {$colors['text_hover']};
}
.woocommerce .woocommerce-message:before,
.woocommerce .woocommerce-info:before {
	color: {$colors['text_link3']};
}
.woocommerce .woocommerce-error:before {
	color: {$colors['alter_link']};
}


/* Cart */
.woocommerce table.shop_table td {
	border-color: {$colors['alter_bd_color']} !important;
	background-color: {$colors['alter_bg_color']};
}
.woocommerce table.shop_table th {
	border-color: {$colors['alter_bd_color']} !important;
	background-color: {$colors['alter_bg_color']};
}
.woocommerce table.shop_table_responsive tr:nth-child(2n) td, 
.woocommerce-page table.shop_table_responsive tr:nth-child(2n) td{
	background-color: {$colors['alter_bg_color']};
}
.woocommerce table.shop_table tfoot th, .woocommerce-page table.shop_table tfoot th {
	color: {$colors['text']};
	border-color: transparent !important;
	background-color: {$colors['alter_bg_color']};
}

.woocommerce div.product form.cart div.quantity, .woocommerce-page div.product form.cart div.quantity, .woocommerce .shop_table.cart div.quantity, .woocommerce-page .shop_table.cart div.quantity {
		border-color: {$colors['input_bd_color']} !important;
}

.woocommerce .quantity input, .woocommerce #content .quantity input, .woocommerce-page .quantity input, .woocommerce-page #content .quantity input {
	background-color: {$colors['inverse_light']};
	border-color: transparent !important
}
.woocommerce .quantity input.qty, .woocommerce #content .quantity input.qty, .woocommerce-page .quantity input.qty, .woocommerce-page #content .quantity input.qty {
	color: {$colors['input_dark']};
}

.woocommerce .cart-collaterals .cart_totals table select,
.woocommerce-page .cart-collaterals .cart_totals table select,
.woocommerce .cart-collaterals .cart_totals table select:before, 
.woocommerce-page .cart-collaterals .cart_totals table select:before{
	color: {$colors['input_text']};
	background-color: {$colors['input_bg_color']};
}

.woocommerce td.product-name dl.variation, .woocommerce td.product-name dl.variation dt{
	color: {$colors['text_light']};
}

.woocommerce .cart-collaterals .cart_totals table select:focus, .woocommerce-page .cart-collaterals .cart_totals table select:focus {
	color: {$colors['input_dark']};
	background-color: {$colors['input_bg_hover']};
}
.woocommerce .cart-collaterals .shipping_calculator .shipping-calculator-button:after,
.woocommerce-page .cart-collaterals .shipping_calculator .shipping-calculator-button:after {
	color: {$colors['text']};
}
.woocommerce table.shop_table .cart-subtotal .amount, 
.woocommerce-page table.shop_table .cart-subtotal .amount,
.woocommerce table.shop_table .shipping td, .woocommerce-page table.shop_table .shipping td {
	color: {$colors['text']};
}
.woocommerce table.cart td+td a, .woocommerce #content table.cart td+td a, .woocommerce-page table.cart td+td a, .woocommerce-page #content table.cart td+td a,
.woocommerce table.cart td+td span, .woocommerce #content table.cart td+td span, .woocommerce-page table.cart td+td span, .woocommerce-page #content table.cart td+td span {
	color: {$colors['text_link']};
}
.woocommerce table.cart td+td a:hover, .woocommerce #content table.cart td+td a:hover, .woocommerce-page table.cart td+td a:hover, .woocommerce-page #content table.cart td+td a:hover {
	color: {$colors['text_hover']};
}
#add_payment_method table.cart td.actions .coupon .input-text, .woocommerce-cart table.cart td.actions .coupon .input-text, .woocommerce-checkout table.cart td.actions .coupon .input-text {
	border-color: {$colors['input_bd_color']};
}

.woocommerce .shipping-calculator-button {
	color: {$colors['inverse_text']};
	background: {$colors['text_hover']};
}

.woocommerce .shipping-calculator-button:hover {
	color: {$colors['text_link']};
	background: {$colors['inverse_text']};
}



/* Checkout */
#add_payment_method #payment ul.payment_methods, .woocommerce-cart #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods {
	border-color:{$colors['bd_color']};
}
#add_payment_method #payment div.payment_box, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box {
	color:{$colors['input_dark']};
	background-color:{$colors['input_bg_hover']};
}
#add_payment_method #payment div.payment_box:before, .woocommerce-cart #payment div.payment_box:before, .woocommerce-checkout #payment div.payment_box:before {
	border-color: transparent transparent {$colors['input_bg_hover']};
}
.woocommerce .order_details li strong, .woocommerce-page .order_details li strong {
	color: {$colors['text']};
}
.woocommerce .order_details.woocommerce-thankyou-order-details {
	color:{$colors['alter_text']};
	background-color:{$colors['alter_bg_color']};
}
.woocommerce .order_details.woocommerce-thankyou-order-details strong {
	color:{$colors['alter_dark']};
}

/* My Account */
.woocommerce-account .woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-navigation ul li,
.woocommerce-MyAccount-navigation li+li {
	border-color: {$colors['bd_color']};
}
.woocommerce-MyAccount-navigation li.is-active a {
	color: {$colors['text_link']};
}

/* Widgets */
.widget_product_search form:after {
	color: {$colors['text']};
}
.widget_product_search form:hover:after {
	color: {$colors['input_dark']};
}
.widget_shopping_cart .total {
	color: {$colors['text']};
}
.widget_layered_nav ul li.chosen a {
	color: {$colors['text']};
}
.widget_price_filter .price_slider_wrapper .ui-widget-content { 
	background: {$colors['text_light']};
}
.widget_price_filter .price_label, .widget_price_filter .price_label span {
	color: {$colors['text_light']};
}

.woocommerce a.remove, 
.woocommerce a.remove{
	color: {$colors['inverse_text']} !important;
	background: {$colors['text_link']};
}
.woocommerce a.remove:hover{
	background: {$colors['text_hover']};
	color: {$colors['inverse_text']} !important;
}

.woocommerce.widget_shopping_cart .buttons .button{
	background-color: {$colors['text_hover']};
}
.woocommerce.widget_shopping_cart .buttons .button:hover{
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']} !important;
}

header .woocommerce.widget_shopping_cart .buttons .button:hover{
	background-color: {$colors['inverse_text']};
	color: {$colors['text_link']} !important;
}

.woocommerce ul.cart_list li img, .woocommerce ul.product_list_widget li img, 
.woocommerce-page ul.cart_list li img, .woocommerce-page ul.product_list_widget li img {
	background-color: {$colors['inverse_text']};
}



/* Third-party plugins
---------------------------------------------- */
.yith_magnifier_zoom_wrap .yith_magnifier_zoom_magnifier {
	border-color: {$colors['bd_color']};
}

.yith-woocompare-widget a.clear-all {
	color: {$colors['inverse_link']};
	background-color: {$colors['alter_link']};
}
.yith-woocompare-widget a.clear-all:hover {
	color: {$colors['inverse_hover']};
	background-color: {$colors['alter_hover']};
}

.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container-single .chosen-single {
	color: {$colors['input_text']};
	background: {$colors['input_bg_color']};
}
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container-single .chosen-single:hover {
	color: {$colors['input_dark']};
	background: {$colors['input_bg_hover']};
}
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-drop {
	color: {$colors['input_dark']};
	background: {$colors['input_bg_hover']};
	border-color: {$colors['input_bd_hover']};
}
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-results li {
	color: {$colors['input_dark']};
}
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-results li:hover,
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-results li.highlighted,
.widget.WOOCS_SELECTOR .woocommerce-currency-switcher-form .chosen-container .chosen-results li.result-selected {
	color: {$colors['alter_link']} !important;
}

CSS;
		}
		
		return $css;
	}
}
?>