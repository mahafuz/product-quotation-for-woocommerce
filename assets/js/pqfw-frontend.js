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
			var t = $( this );

			t.on( "click", "#rsrfqfwc_submit", function ( ev ) {
				ev.preventDefault();

				var t = $( this ),
					f = t.parents( "#pqfw-frontend-form" ),
					l = f.find( ".pqfw-frontend-form" ).children( "li" ),
					input    = f.find( "input" ),
					textarea = f.find( "textarea" );

				var $input, $textarea, $this;
				l.each(function () {
					$this   = $(this);
					$input  = $this.children( "input" );

					if ( $input.length === 0 ) {
						$textarea = $this.children( "textarea" );

						if ( $textarea.prop( "required" ) ) {
							$textarea.val() == ""
								? $this.addClass( "hasError" )
								: $this.removeClass( "hasError" );
						}
					} else {
						if ( $input.prop( "required" ) ) {
							$input.val() == ""
								? $this.addClass( "hasError" )
								: $this.removeClass( "hasError" );
						}
					}
				});

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

				if (!$.isEmptyObject(data)) {
					// TODO: DO AJAX
				}

				return false;
			});
		})
		.trigger("pqfw_init");
});
