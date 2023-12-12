elementor.hooks.addAction('panel/open_editor/widget', function(panel, model, view) {
	var pqfwCart = view.$el.find( '.woocommerce table > tbody#pqfw-quotations-list-row' );
	var formWrap = view.$el.find( '.woocommerce #pqfw-frontend-form-wrap' );

		function visibleForm( products ) {
			if (products == null || products.length == 0) {
				formWrap.css( "display", "none" );
			} else {
				formWrap.css( "display", "block" );
			}
		};

		function dataLoaded(response) {
			pqfwCart.html( response.html );
			visibleForm(response.products);
		};

		wp.ajax.send(
			'pqfw_load_cart_data',
			{
				data : {
					nonce : PQFW_OBJECT.nonce
				},
				success : function( response ) {
					console.log('response', response);
					dataLoaded(response);
				},
				error   : function( response ) {
					console.error(response);
				}
			}
		);
});