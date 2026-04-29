<?php
/**
 * Product List - Detailed (Admin View)
 *
 * Shows products with images, prices, and full details.
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
	'heading'  => __( 'Requested Products', 'quotify' ),
];

$data = wp_parse_args( $data, $defaults );

if ( empty( $data['products'] ) ) {
	return;
}
?>

<?php if ( ! empty( $data['heading'] ) ) : ?>
<h4 class="section-heading"><?php echo esc_html( $data['heading'] ); ?></h4>
<?php endif; ?>

<?php
foreach ( $data['products'] as $product ) :
	$product = quotify_format_email_product( $product );
	?>
	<div class="product-grid">
		<?php if ( ! empty( $product['img'] ) ) : ?>
		<div class="product-image">
			<a href="<?php echo esc_url( $product['link'] ); ?>">
				<img src="<?php echo esc_url( $product['img'] ); ?>" alt="<?php echo esc_attr( $product['name'] ); ?>"
					loading="lazy" />
			</a>
		</div>
		<?php endif; ?>

		<div class="product-title">
			<a href="<?php echo esc_url( $product['link'] ); ?>" style="color: inherit; text-decoration: none;">
				<?php echo esc_html( $product['name'] ); ?>
			</a>
		</div>

		<div class="product-detail">
			<strong><?php esc_html_e( 'Quantity:', 'quotify' ); ?></strong>
			<?php echo esc_html( $product['quantity'] ); ?>
		</div>

		<?php if ( ! empty( $product['price'] ) ) : ?>
		<div class="product-detail product-price">
			<strong><?php esc_html_e( 'Price:', 'quotify' ); ?></strong>
			<?php echo esc_html( $product['price'] ); ?>
		</div>
		<?php endif; ?>

		<?php if ( ! empty( $product['message'] ) ) : ?>
		<div class="product-detail">
			<strong><?php esc_html_e( 'Product Note:', 'quotify' ); ?></strong>
			<?php echo esc_html( $product['message'] ); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php
	/**
	 * Fires after each product in detailed list.
	 *
	 * @since 2.6.0
	 *
	 * @param array $product Product data.
	 */
	do_action( 'quotify_email_after_product_detailed', $product );
endforeach;

/**
 * Fires after detailed product list.
 *
 * @since 2.6.0
 *
 * @param array $data Product list data.
 */
do_action( 'quotify_email_after_products_detailed', $data );
