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
				f = $( '#pqfw-frontend-form' ),
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
					nonce = f.find( 'input[name="pqfw_form_nonce_field"]').val(),
					loader = t.next('.loading-spinner');

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
						if ( $input.attr( "type" ) === "email" ) {
							if( $input.prop( "required" ) ) {
								if( $input.val() !== '' && emailReg.test( $input.val() ) ) {
									$this.removeClass( "hasError" );
									errors = false;
								}else {
									$this.addClass( "hasError" );
									errors = true;
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
									resposneStatus.html( response.data );

									input.each( function () {
										$( this ).val( '' );
									} );

									textarea.each(function () {
										$( this ).val( '' );
									});

									resposneStatus.removeClass('error');
									resposneStatus.addClass('success');
									resposneStatus.html( response.data );

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
