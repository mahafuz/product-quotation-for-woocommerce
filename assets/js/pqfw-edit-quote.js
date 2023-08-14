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
				single_price_inc_tax = parseFloat( $this.data('price-inc-tax').replace('$', '') );
			} else {
				single_price_inc_tax = parseFloat( $this.data('price-inc-tax') );
			}

			var updated_price = (single_price_inc_tax * new_quantity);

			price_including_tax_input.val('$' + updated_price);

			var total_price_inc_tax = $('.pqfw-product-regular-price');

			total_price_inc_tax = pqfwCart.calculateTotal(total_price_inc_tax);

			$('#display-total-price-inc-tax').text( '$' + total_price_inc_tax );


			if ( $this.data('exc-tax-price').toString().includes('$')) {
				// price excluding tax.
				var single_price_exc_tax = parseFloat( $this.data('exc-tax-price').replace('$', '') );
			} else {
				var single_price_exc_tax = parseFloat( $this.data('exc-tax-price') );
			}

			var exc_tax_price_value = $this.parents('.pqfw-list-of-single-product').find('.pqfw-product-price-exc-tax');
			var single_exc_tax = (single_price_exc_tax * new_quantity);
			exc_tax_price_value.val('$' + single_exc_tax);

			var total_price_exc_tax = $('.pqfw-product-price-exc-tax');
			total_price_exc_tax = pqfwCart.calculateTotal(total_price_exc_tax );
			$('#display-total-price-exc-tax').text( '$' +( total_price_exc_tax.toFixed(2) ) );
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

		},
		calculateTotal: function(prices) {
			var total = 0;
			prices.each(function() {
				total += parseFloat($(this).val().replace('$', ''));
			});

			return total;
		},
	};

	pqfwCart.init();
	window.pqfwCart = pqfwCart;

})(jQuery);
