<?php

namespace PQFW\Classes\Addons;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

use \Elementor\Widget_Base;

/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 2.0.3
 */
class Elementor extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 2.0.3
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pqfw-quote-cart';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 2.0.3
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Quotation Cart', 'pqfw' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 2.0.3
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'pqfw-quote-cart-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 2.0.3
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Sets searchable keywords on the widget.
	 *
	 * @since 2.0.3
	 */
	public function get_keywords() {
		return [
			'pqfw',
			'quote',
			'enquire',
			'products',
			'woo',
			'rfq',
			'quotation'
		];
	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function render() {
		$shortcode = '[pqfw_quotations_cart]';
		echo do_shortcode( shortcode_unautop( $shortcode ) );
		?>
			<script>
				(function($) {
					var pqfwCart = $('#pqfw-quotations-list-row');

					function visibleForm( products ) {
						if (products == null || products.length == 0) {
							$("#pqfw-frontend-form-wrap").css( "display", "none" );
						} else {
							$("#pqfw-frontend-form-wrap").css( "display", "block" );
						}
					};

					function dataLoaded(response) {
						$('#pqfw-quotations-list-row').html( response.html );
						visibleForm(response.products);
					};

					wp.ajax.send(
						'pqfw_load_cart_data',
						{
							data : {
								nonce : PQFW_OBJECT.nonce
							},
							success : function( response ) {
								dataLoaded(response);
							},
							error   : function( response ) {
								console.error(response);
							}
						}
					);
				})(jQuery);
			</script>
		<?php
	}

}