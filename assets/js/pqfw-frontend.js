/**
 * PQFW frontend JS.
 *
 * @author Mahafuz
 * @package PQFW
 * @version 1.0.0
 */

jQuery(function ( $ ) {

	$( document )
		.on( "pqfw_init", function () {

			var t = $( this ),
				u = $( '.pqfw-frontend-form' ),
				l = u.children( 'li' ),
				input = l.find( 'input' ),
				textarea = l.find( 'textarea' ),
				emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
				errors = null;


			t.on( "click", "#rsrfqfwc_submit", function ( ev ) {

				ev.preventDefault();

				var t = $( this );

				// validating fields empty value.
				var $input, $textarea, $this;
				l.each( function () {
					$this   = $(this);
					$input  = $this.children( "input" );

					if ( $input.length === 0 ) {
						$textarea = $this.children( "textarea" );

						if ( $textarea.prop( "required" ) ) {
							if( $textarea.val() == "" ) {
								$this.addClass( "hasError" );
								errors = true;
							}else {
								$this.removeClass( "hasError" );
								errors = false;
							}
						}
					} else {
						if ( $input.prop( "required" ) ) {
							if( $input.val() == "" ) {
								$this.addClass( "hasError" );
								errors = true;
							}else {
								$this.removeClass( "hasError" );
								errors = false;
							}
						}
						if( $input.attr( "type" ) === "email" ) {
							if( $input.val() !== '' && emailReg.test( $input.val() ) ) {
								$this.removeClass( "hasError" );
								errors = true;
							}else {
								$this.addClass( "hasError" );
								errors = false;
							}
						}
					}
				});


				if( !errors ) {
					// preparing data
					var data = {};

					if ( input.length > 2 ) {
						input.each( function () {
							data[ $(this).attr("name") ] = $( this ).val();
						} );
					}

					if (textarea.length >= 1) {
						textarea.each(function () {
							data[ $(this).attr("name") ] = $( this ).val();
						});
					}

					if ( ! $.isEmptyObject( data ) ) {
						// TODO: DO AJAX
					}
				}else {
					return false;
				}

			});
		})
		.trigger("pqfw_init");

});
