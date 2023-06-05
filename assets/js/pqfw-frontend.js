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
				inputs = $( '#pqfw-frontend-form-wrap .form-group' ),
				u = $( '.pqfw-frontend-form' ),
				l = u.children( 'li' ),
				input = l.find( 'input' ),
				textarea = l.find( 'textarea' ),
				emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
				errors = null,
				resposneStatus = $('.pqfw-form-response-status');

			t.on( "click", "#rsrfqfwc_submit", function ( ev ) {

				ev.preventDefault();

				var t = $( this ),
					nonce = $( '#pqfw_form_nonce_field').val(),
					loader = t.next('.loading-spinner'),
					data = {};

				// validating fields empty value.
				inputs.each( function () {
					$this   = $(this);
					$input  = $this.children( "input" );

					if ( $input.length === 0 ) {
						$textarea = $this.children( "textarea" );

						if ( $textarea.length ) {
							if ( $textarea.prop( "required" ) ) {
								if( $textarea.val() == "" ) {
									$this.addClass( "hasError" );
									errors = true;
									data[ $textarea.prop('name') ] = $textarea.val();
								}else {
									$this.removeClass( "hasError" );
									errors = false;
								}
							} else {
								data[ $textarea.prop('name') ] = $textarea.val();
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
								data[ $input.prop('name') ] = $input.val();
							}
						} else {
							data[ $input.prop('name') ] = $input.val();
						}
						if ( $input.attr( "type" ) === "email" ) {
							if( $input.prop( "required" ) ) {
								if( $input.val() !== '' && emailReg.test( $input.val() ) ) {
									$this.removeClass( "hasError" );
									errors = false;
								}else {
									$this.addClass( "hasError" );
									errors = true;
									data[ $input.prop('name') ] = $input.val();
								}
							}
						}
					}
				});

				if ( ! errors ) {
					var privacyPolicy = $( '#pqfw_privacy_policy_checkbox' );
					if ( privacyPolicy.length && ! privacyPolicy.prop('checked') ) {
						errors = true;
						privacyPolicy.parents( '.pqfw-privacy-policy' ).addClass('hasError');
						alert( 'Please accept privacy policy If you want to proceed.' );
					}
				}



				if ( ! errors ) {
					if ( ! $.isEmptyObject( data ) ) {
						data['action']		= 'pqfw_quotation_submission';
						data['nonce']		= nonce;

						$.ajax({
							type: 'POST',
							url: PQFW_OBJECT.ajaxurl,
							data: data,
							beforeSend: function() {
								loader.addClass('loading');
							},
							complete: function() {
								loader.removeClass('loading');
							},
							success: function( response ) {
								if( response.success ) {
									resposneStatus.removeClass('error');
									resposneStatus.addClass('success');
									resposneStatus.html( response.data.message );

									input.each( function () {
										$( this ).val( '' );
									} );

									textarea.each(function () {
										$( this ).val( '' );
									});

									resposneStatus.removeClass('error');
									resposneStatus.addClass('success');
									resposneStatus.html( response.data.message );

									setTimeout(function() {
										window.pqfwCart.initialize();
									}, 1000);
								}else {
									resposneStatus.removeClass('success');
									resposneStatus.addClass('error');

									let html = '';

									if( $.type(response.data) == 'object' ) {
										$.each( response.data, function(key, value) {
											html += '<p class="'+key+'">';
											html += value;
											html += '</p>';
										});
									}

									resposneStatus.html( html );
								}
							}
						});
					}
				}else {
					return false;
				}

			});
		})
		.trigger("pqfw_init");
});
