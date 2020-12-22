/**
 * PQFW settings JS.
 *
 * @author Mahafuz
 * @package PQFW
 * @version 1.0.0
 */

jQuery(function ( $ ) {
    $(document).ready(function() {
        var switch_control = $( '.pqfw-switch-control' ),
            nonce = $( '#pqfw-settings-form' ).find( 'input[name="_wpnonce"]' ).val();

        switch_control.on( 'change', function( ev ) {

            var t = $(this),
                name = t.attr( 'name' );

            var data = {
                _wpnonce: nonce,
                name: name,
                status: t.prop('checked'),
                action: PQFW_OBJECT.actions.save_settings
            }

            $.ajax({
                url: PQFW_OBJECT.ajaxurl,
                dataType: "json",
                data: data,
                success: function( response ) {
                    console.log( response );
                }
            });

        } );
    });
});