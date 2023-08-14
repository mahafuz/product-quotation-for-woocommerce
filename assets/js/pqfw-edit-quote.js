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
			console.log( 'running 1' );

			// Price including taxes.
			var price_including_tax_input = $this.parents('.pqfw-list-of-single-product').find('.pqfw-product-regular-price');
			
			var single_price_inc_tax = parseFloat( $this.data('price-inc-tax').replace('$', '') );

			var updated_price = (single_price_inc_tax * new_quantity);

			price_including_tax_input.val('$' + updated_price);

			var total_price_inc_tax = $('.pqfw-product-regular-price');

			total_price_inc_tax = pqfwCart.calculateTotal(total_price_inc_tax);
			console.log( total_price_inc_tax );

			$('#display-total-price-inc-tax').text( '$' + total_price_inc_tax );

			console.log( 'running 2', $('#display-total-price-inc-tax') );

			// price excluding tax.
			var single_price_exc_tax = parseFloat( $this.data('exc-tax-price').replace('$', '') );

			var exc_tax_price_value = $this.parents('.pqfw-list-of-single-product').find('.pqfw-product-price-exc-tax');
			var single_exc_tax = (single_price_exc_tax * new_quantity);
			exc_tax_price_value.val('$' + single_exc_tax);

			var total_price_exc_tax = $('.pqfw-product-price-exc-tax');
			total_price_exc_tax = pqfwCart.calculateTotal(total_price_exc_tax );
			$('#display-total-price-exc-tax').text( '$' +( total_price_exc_tax.toFixed(2) ) );
		},

		init: function () {
			var quantities = $('.pqfw-quote-edit-quantity');

			quantities.each(function(){
				pqfwCart.editScreen( $(this) );
			});

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
