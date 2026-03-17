/**
 * Quotify frontend JS.
 *
 * @author Mahafuz
 * @package Quotify
 * @version 1.0.0
 */
import { makeRequest } from '@Utils/global';

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
				responseStatus = $('.pqfw-form-response-status');

			t.on( "click", "#quotify-form-submit", function ( ev ) {
				ev.preventDefault();

				var t = $( this ),
					loader = t.next('.loading-spinner');

				responseStatus.removeClass('success error').html('');
				responseStatus.hide();

				errors = false;

				l.removeClass('hasError');
				$('.pqfw-privacy-policy').removeClass('hasError');

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
							}
						}
					} else {
						if ( $input.prop( "required" ) ) {
							if( $input.val() == "" ) {
								$this.addClass( "hasError" );
								errors = true;
							}
						}
						if ( $input.attr( "type" ) === "email" ) {
							if( $input.prop( "required" ) || $input.val() !== '' ) {
								if( ! emailReg.test( $input.val() ) ) {
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
						responseStatus.addClass('error');
						responseStatus.html( '<p class="quotify-form-validation-error">Please accept privacy policy to proceed.</p>' );
						responseStatus.show();
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
						// Show loading state
						loader.addClass('loading');
						t.prop('disabled', true);

						makeRequest({
							action: 'quotify/ajax/quotation/submit',
							data,
						}).then(function(response) {
							// Hide loading state
							loader.removeClass('loading');
							t.prop('disabled', false);

							// Handle axios response structure: response.data.data
							var responseData = response?.data?.data;

							if( response?.data?.success ) {
								responseStatus.removeClass('error');
								responseStatus.addClass('success');
								responseStatus.html( responseData );

								input.each( function () {
									$( this ).val( '' );
								} );

								textarea.each(function () {
									$( this ).val( '' );
								});

								// Uncheck privacy policy checkbox
								var privacyPolicy = $( '#pqfw_privacy_policy_checkbox' );
								if ( privacyPolicy.length ) {
									privacyPolicy.prop('checked', false);
								}

								responseStatus.show();

								setTimeout(function() {
									window.QuotifyCart.initialize();
								}, 500);
							} else {
								responseStatus.removeClass('success');
								responseStatus.addClass('error');

								let html = '';

								if ( $.type( responseData ) === 'object' && responseData !== null ) {
									// Handle WP_Error structure where errors are indexed by code (e.g., 'field': [error1, error2])
									for ( var code in responseData ) {
										if ( responseData.hasOwnProperty( code ) ) {
											var errorMessages = responseData[ code ];
											// errorMessages can be a string or array
											if ( $.isArray( errorMessages ) ) {
												$.each( errorMessages, function( index, message ) {
													html += '<p class="quotify-form-validation-error">' + message + '</p>';
												});
											} else if ( typeof errorMessages === 'string' ) {
												html += '<p class="quotify-form-validation-error">' + errorMessages + '</p>';
											}
										}
									}
								} else if ( typeof responseData === 'string' ) {
									// Generic error message (e.g., rate limit, security check).
									html = '<p class="quotify-form-validation-error">' + responseData + '</p>';
								}

								responseStatus.html( html );
								responseStatus.show();
							}
						}).catch(function(error) {
							// Hide loading state
							loader.removeClass('loading');
							t.prop('disabled', false);

							responseStatus.removeClass('success');
							responseStatus.addClass('error');
							responseStatus.html( '<p class="quotify-form-validation-error">An error occurred. Please try again.</p>' );
							responseStatus.show();

							console.error('Form submission error:', error);
						});
					}
				}else {
					// Scroll to first error
					var firstError = $( '.hasError' ).first();
					if ( firstError.length ) {
						$( 'html, body' ).animate({
							scrollTop: firstError.offset().top - 50
						}, 300 );
					}
					return false;
				}

			});
		})
		.trigger("pqfw_init");
});
