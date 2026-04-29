<?php
/**
 * Product List - Simple (Customer View)
 *
 * Shows simplified product list without images or pricing.
 *
 * @since 2.6.0
 * @package Quotify
 *
 * @param array $data {
 *     Product data.
 *
 *     @type array  $products Array of products.
 *     @type string $heading  Optional heading.
 * }
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$defaults = [
	'products' => [],
	'heading'  => __( 'Your Request Summary', 'quotify' ),
];

$data = wp_parse_args( $data, $defaults );

if ( empty( $data['products'] ) ) {
	return;
}
?>

<div class="product-summary">
	<?php if ( ! empty( $data['heading'] ) ) : ?>
		<h4 style="margin-top: 0; margin-bottom: 15px; color: #2c3e50; font-size: 16px;">
			<?php echo esc_html( $data['heading'] ); ?>
		</h4>
	<?php endif; ?>

	<?php
	foreach ( $data['products'] as $product ) :
		$product = quotify_format_email_product( $product );
		?>
		<div class="product-item">
			<div class="product-name"><?php echo esc_html( $product['name'] ); ?></div>
			<div class="product-quantity">
				<?php esc_html_e( 'Quantity:', 'quotify' ); ?>
				<?php echo esc_html( $product['quantity'] ); ?>
			</div>
		</div>

		<?php
		/**
		 * Fires after each product in simple list.
		 *
		 * @since 2.6.0
		 *
		 * @param array $product Product data.
		 */
		do_action( 'quotify_email_after_product_simple', $product );
	endforeach;

	/**
	 * Fires after simple product list.
	 *
	 * @since 2.6.0
	 *
	 * @param array $data Product list data.
	 */
	do_action( 'quotify_email_after_products_simple', $data );
	?>
</div>
