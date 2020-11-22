/**
 * PQFW frontend JS.
 *
 * @author Mahafuz
 * @package PQFW
 * @version 1.0.0
 */

jQuery(function( $ ) {

    $(document).on( 'pqfw_init', function() {
        var t = $(this);

        t.on( 'click', '#rsrfqfwc_submit', function( ev ) {
            ev.preventDefault();

            var t = $(this),
                f = t.parents( '#pqfw-frontend-form' ),
                input = f.find( 'input' ),
                textarea = f.find( 'textarea' );

                var data = {};

                if( input.length > 2) {
                    input.each( function( _, item ) {
                        data[$( item ).attr('name')] = $( item ).val();
                    } );
                }

                if( textarea.length >= 1 ) {
                    textarea.each( function( _, item ) {
                        data[$( item ).attr( 'name' )] = $( item ).val();
                    } );
                }

                if( ! $.isEmptyObject( data ) ) {
                    // TODO: DO AJAX
                }

                return false;

        } );

    }).trigger('pqfw_init');

});