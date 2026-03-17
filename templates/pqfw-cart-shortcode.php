<?php
/**
 * Woocommerce cart view template.
 *
 * @since 1.0.0
 * @package Quotify
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce">
	<form class="woocommerce-cart-form">
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-remove">&nbsp;</th>
					<th class="product-thumbnail">&nbsp;</th>
					<th class="product-name">
						<?php esc_html_e( 'Product', 'quotify' ); ?>
					</th>
					<th class="product-price"><?php esc_html_e( 'Price', 'quotify' ); ?></th>
					<th class="product-quantity">
						<?php esc_html_e( 'Quantity', 'quotify' ); ?>
					</th>
					<th class="product-subtotal">
						<?php esc_html_e( 'Message', 'quotify' ); ?>
					</th>
				</tr>
			</thead>
			<tbody id="pqfw-quotations-list-row"></tbody>
		</table>
	</form>

	<?php
		do_action( 'quotify/templates/cart/form' );
	?>
</div>
