import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import { createPortal } from '@wordpress/element';
import { makeRequest } from '@Utils/helper';
import { getQuantity, getVariationDetails, getVariationID } from './utils/helper';

document.addEventListener( 'DOMContentLoaded', () => {
	console.log( 'loading button file' );

	const $ = jQuery;

	$( document ).on(
		'click',
		'.pqfw-add-to-quotation-single',
		function ( event ) {
			event.preventDefault();

			const $product_id = $(this).data('id');
			
			wp.ajax.send(
				'quotify/product/add',
				{
					data   : {
						productId : parseInt( $product_id ),
						variationID: parseInt( getVariationID() ),
						variationDetails: getVariationDetails(),
						quantity: parseInt(getQuantity()),
						security : PqfwGlobal.pqfw_nonce
					},
					success : function( response ) {
						console.log('response', response);
					},
					error   : function( error ) {
						console.log( error );
					}
				}
			)
		}
	);
} );
