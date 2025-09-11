<?php
/**
 * Contains all public helper functions.
 *
 * @since 2.4.0
 * @package Quotify
 *
 * @return mixed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'pqfwGetPreLoader' ) ) {
	/**
	 * Get preloader.
	 *
	 * @return mixed
	 */
	function pqfwGetPreLoader() {
		ob_start();
		?>
			<div class="pqfw-initial-preloader">
				<img src="<?php echo esc_url( PQFW_PLUGIN_ASSETS . '/images/preloader.apng' ); ?>" alt="product-quotation-for-woocommerce">
			</div>
		<?php
		return ob_get_clean();
	}
}
