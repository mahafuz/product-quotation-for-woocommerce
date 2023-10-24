(function ($) {
	'use strict';

	/**
	 * This file is responsible for handling the product quotation edit screen interactive functionalities. 
	 * 
	 * @since 2.0.4
	 */
	var pqfwCart = {
		editScreen: function( $this ) {
			var new_quantity = $this.val();

			// Price including taxes.
			var price_including_tax_input = $this.parents('.pqfw-list-of-single-product').find('.pqfw-product-regular-price');
			var single_price_inc_tax = '';
			
			if ( $this.data('price-inc-tax').toString().includes('$') ) {
				single_price_inc_tax = parseFloat( $this.attr('data-price-inc-tax').replace('$', '') );
			} else {
				single_price_inc_tax = parseFloat( $this.attr('data-price-inc-tax') );
			}

			var updated_price = parseFloat(single_price_inc_tax * new_quantity).toFixed(2);

			price_including_tax_input.val('$' + updated_price);

			var total_price_inc_tax = $('.pqfw-product-regular-price');

			total_price_inc_tax = pqfwCart.calculateTotal(total_price_inc_tax);

			$('#display-total-price-inc-tax').text( '$' + parseFloat( total_price_inc_tax ) );
		},

		init: function () {
			var quantities = $('.pqfw-quote-edit-quantity');

			if ( quantities.length ) {

				quantities.each(function(){
					pqfwCart.editScreen( $(this) );
				});
			}

			$(document).on('change', '.pqfw-quote-edit-quantity', function () {
				pqfwCart.editScreen($(this));
			});

			$(document).on('change', '.pqfw-product-regular-price', function() {
				var total_regular_price = $('.pqfw-product-regular-price');
				total_regular_price = pqfwCart.calculateTotal(total_regular_price);

				var closest_quantity = $(this).parents('.pqfw-list-of-single-product').find('.pqfw-quote-edit-quantity');
				
				closest_quantity.attr('data-price-inc-tax', ( parseFloat( $(this).val().replace('$', '') ) / parseInt( closest_quantity.val() ) ) );

				$('#display-total-price-inc-tax').text( '$' + parseFloat( total_regular_price.toFixed(2) ) );
			});
		},
		calculateTotal: function(prices) {
			var total = 0;
			prices.each(function() {
				total += parseFloat($(this).val().replace('$', ''));
			});

			return total;
		},
	};

	$(document).ready(function() {
		pqfwCart.init();
		window.pqfwCart = pqfwCart;
	});

})(jQuery);
