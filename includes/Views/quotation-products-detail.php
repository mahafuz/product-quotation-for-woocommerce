<?php
/**
 * Displays quotaion products details.
 *
 * @since 1.2.0
 * @package PQFW
 */

$table_columns = pqfw()->settings->get( 'cart_table_columns' );
$table_columns = wp_list_pluck( $table_columns, 'value' );
?>
<div class="pqfw-quotation-produts-detail">
	<table class="pqfw-list-products widefat fixed striped table-view-list">
		<thead>
			<tr>
				<?php if ( empty( $table_columns ) || in_array( 'thumbnail', $table_columns, true ) ) : ?>
				<th class="pqfw-product-thumbnail pqfw-list-products-head"><?php esc_html_e( 'Image', 'pqfw' ); ?></th>
				<?php endif; ?>

				<?php if ( empty( $table_columns ) || in_array( 'product', $table_columns, true ) ) : ?>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Product', 'pqfw' ); ?></th>
				<?php endif; ?>

				<?php if ( empty( $table_columns ) || in_array( 'price', $table_columns, true ) ) : ?>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Price', 'pqfw' ); ?></th>
				<?php endif; ?>

				<?php if ( empty( $table_columns ) || in_array( 'quantity', $table_columns, true ) ) : ?>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Quantity', 'pqfw' ); ?></th>
				<?php endif; ?>

				<?php if ( empty( $table_columns ) || in_array( 'message', $table_columns, true ) ) : ?>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Message', 'pqfw' ); ?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody class="pqfw-list-products-body">
			<?php $quoteProducts = unserialize( get_post_meta( $quotation->ID, 'pqfw_products_info', true ) ); ?>
			<?php if ( is_array( $quoteProducts ) ) : ?>
				<?php foreach ( $quoteProducts as $product ) : ?>
				<tr class="pqfw-list-of-single-product">

					<?php if ( empty( $table_columns ) || in_array( 'thumbnail', $table_columns, true ) ) : ?>
					<td class="pqfw-product-thumbnail">
						<?php if ( ! empty( $product['img'] ) ) : ?>
							<a href="<?php echo esc_url( $product['link'] ); ?>" target="_blank">
								<img src="<?php echo esc_url( $product['img'] ); ?>">
							</a>
						<?php endif; ?>
					</td>
					<?php endif; ?>

					<?php if ( empty( $table_columns ) || in_array( 'product', $table_columns, true ) ) : ?>
					<td>
						<a href="<?php echo esc_url( $product['link'] ); ?>" target="_blank">
							<?php echo esc_html( $product['name'] ); ?>
						</a><br>
						<?php pqfw()->helpers->build_variations( $product['variation_detail'] ); ?>
					</td>
					<?php endif; ?>

					<?php if ( empty( $table_columns ) || in_array( 'price', $table_columns, true ) ) : ?>
					<td>
						<span class="pqfw-cart-price-inc-tax"><strong><?php echo isset( $product['regular_price'] ) ? esc_attr( $product['regular_price'] ) : ''; ?></strong></span>
						<?php if ( ! empty( $product['exc_tax_price'] ) ) : ?>
						<?php 
							$suffix = get_option( 'woocommerce_price_display_suffix', '' );
							$suffix = str_replace( '{price_excluding_tax}', '', $suffix );
						?>
						<span class="pqfw-cart-price-exc-tax"><?php echo esc_attr( $suffix ); ?><?php echo esc_attr( $product['exc_tax_price'] ); ?></span>
						<?php endif; ?>
					</td>
					<?php endif; ?>

					<?php if ( empty( $table_columns ) || in_array( 'quantity', $table_columns, true ) ) : ?>
					<td><strong><?php echo esc_html( $product['quantity'] ); ?></strong></td>
					<?php endif; ?>

					<?php if ( empty( $table_columns ) || in_array( 'message', $table_columns, true ) ) : ?>
					<td><?php echo esc_html( $product['message'] ); ?></td>
					<?php endif; ?>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>