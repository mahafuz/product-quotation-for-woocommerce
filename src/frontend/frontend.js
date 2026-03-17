/* global ywraq_frontend */
jQuery(function ($) {
	"use strict";

	var $body = $('body'),
		table = $(document).find('#yith-ywrq-table-list'),
		url = document.location.href;

	if (table.length > 0 && ywraq_frontend.raq_table_refresh_check) {
		$.post(url, function (data) {
			if ( '' !== data ) {
				var c = $("<div></div>").html(data),
					table = c.find('#yith-ywrq-table-list');
				$('#yith-ywrq-table-list').html(table.html());
				$(document).trigger('ywraq_table_reloaded');
			}
		});
	}

	if ($body.hasClass('single-product')) {

		var nearCart = $(document).find('.near-add-to-cart');
		var variation = $(document).find('.woocommerce-variation-add-to-cart');

		if( nearCart.length > 0 && variation.length > 0 ){
			variation.append( nearCart );
		}
		var $product_id = $('[name|="product_id"]'),
			product_id = $product_id.val(),
			button = $('.add-to-quote-' + product_id).find('a'),
			$button_wrap = button.parents('.yith-ywraq-add-to-quote'),
			$variation_id = $('[name|="variation_id"]');

		$variation_id.on('change', function () {

			if ('' === $(this).val() ) {
				button.parent().hide().removeClass('show');
			} else {
				$.ajax({
					type: 'POST',
					url: ywraq_frontend.ajaxurl,
					dataType: 'json',
					data: 'action=yith_ywraq_action&ywraq_action=variation_exist&variation_id=' + $variation_id.val() + '&product_id=' + $product_id.val() + '&_wpnonce=' + ywraq_frontend.yith_ywraq_action_nonce,
					success: function (response) {
						if (response.result === true) {
							button.parent().hide().removeClass('show');
							if ($('.yith_ywraq_add_item_browse-list-' + $product_id.val()).length == 0) {
								$button_wrap.append('<div class="yith_ywraq_add_item_response-' + $product_id.val() + ' yith_ywraq_add_item_response_message">' + response.message + '</div>');
								$button_wrap.append('<div class="yith_ywraq_add_item_browse-list-' + $product_id.val() + ' yith_ywraq_add_item_browse_message"><a href="' + response.rqa_url + '">' + response.label_browse + '</a></div>');
							}
						} else {
							$('.yith_ywraq_add_item_response-' + $product_id.val()).remove();
							$('.yith_ywraq_add_item_browse-list-' + $product_id.val()).remove();
							button.parent().show().addClass('show');
						}
					}
				});
			}

		});
	}


	$(document).on('click', '.add-request-quote-button', function (e) {
		e.preventDefault();

		var $t = $(this),
			$t_wrap = $t.parents('.yith-ywraq-add-to-quote'),
			add_to_cart_info = 'ac',
			$add_to_cart_el = null,
			$product_id_el = null;

		if ($t.parents('ul.products').length > 0) {
			    $add_to_cart_el = $t.parents('li.product').find('input[name="add-to-cart"]');
				$product_id_el = $t.parents('li.product').find('input[name="product_id"]');
		} else {
			    $add_to_cart_el = $t.parents('.product').find('input[name="add-to-cart"]');
				$product_id_el = $t.parents('.product').find('input[name="product_id"]');
		}

		if ($add_to_cart_el.length > 0 && $product_id_el.length > 0) { //variable product
			add_to_cart_info = $('.cart').serialize();
		} else if ($('.cart').length > 0) { //single product and form exists with cart class
			add_to_cart_info = $('.cart').serialize();
		}

		add_to_cart_info += '&action=yith_ywraq_action&ywraq_action=add_item&product_id=' + $t.data('product_id') + '&_wpnonce=' + ywraq_frontend.yith_ywraq_action_nonce;
		if (add_to_cart_info.indexOf('add-to-cart') > 0) {
			add_to_cart_info = add_to_cart_info.replace('add-to-cart', 'yith-add-to-cart');
		}

		$.ajax({
			type: 'POST',
			url: ywraq_frontend.ajaxurl,
			dataType: 'json',
			data: add_to_cart_info,
			beforeSend: function () {
				$t.siblings('.ajax-loading').css('visibility', 'visible');
			},
			complete: function () {
				$t.siblings('.ajax-loading').css('visibility', 'hidden');
			},

			success: function (response) {
				if (response.result == 'true' || response.result == 'exists') {

					if (ywraq_frontend.go_to_the_list === 'yes') {
						window.location.href = response.rqa_url;
					} else {
						$t.parent().hide().removeClass('show').addClass('addedd');
						var prod_id = (typeof $product_id_el.val() == 'undefined') ? '' : '-' + $product_id_el.val();
						$t_wrap.append('<div class="yith_ywraq_add_item_response' + prod_id + ' yith_ywraq_add_item_response_message">' + response.message + '</div>');
						$t_wrap.append('<div class="yith_ywraq_add_item_browse-list' + prod_id + ' yith_ywraq_add_item_browse_message"><a href="' + response.rqa_url + '">' + response.label_browse + '</a></div>');
					}

				} else if (response.result == 'false') {
					$t_wrap.append('<div class="yith_ywraq_add_item_response-' + $product_id_el.val() + '">' + response.message + '</div>');
				}
			}
		});


	});


	/*Remove an item from rqa list*/
	$(document).on('click', '.yith-ywraq-item-remove', function (e) {

		e.preventDefault();

		var $t = $(this),
			key = $t.data('remove-item'),
			form = $('#yith-ywraq-form'),
			remove_info = '';

		remove_info = 'action=yith_ywraq_action&ywraq_action=remove_item&key=' + $t.data('remove-item') + '&_wpnonce=' + ywraq_frontend.yith_ywraq_action_nonce + '&product_id=' + $t.data('product_id');

		$.ajax({
			type: 'POST',
			url: ywraq_frontend.ajaxurl,
			dataType: 'json',
			data: remove_info,
			beforeSend: function () {
				$t.siblings('.ajax-loading').css('visibility', 'visible');
			},
			complete: function () {
				$t.siblings('.ajax-loading').css('visibility', 'hidden');
			},

			success: function (response) {
				if (response === 1) {
					$("[data-remove-item='" + key + "']").parents('.cart_item').remove();
					if ($('.cart_item').length === 0) {
						$('#yith-ywraq-form, #yith-ywraq-mail-form, .yith-ywraq-mail-form-wrapper').remove();
						$('#yith-ywraq-message').text(ywraq_frontend.no_product_in_list);
					}
				}
			}
		});
	});

	$(document).find('.theme-yith-proteo.yith-request-a-quote-page .woocommerce-message').removeAttr('role');



});