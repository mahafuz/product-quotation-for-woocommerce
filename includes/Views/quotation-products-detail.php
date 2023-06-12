<?php
/**
 * Displays quotaion products details.
 *
 * @since 1.2.0
 * @package PQFW
 */
?>
<div class="pqfw-quotation-produts-detail">
	<table class="pqfw-list-products widefat fixed striped table-view-list">
		<thead>
			<tr>
				<th class="pqfw-product-thumbnail pqfw-list-products-head"><?php esc_html_e( 'Image', 'pqfw' ); ?></th>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Product', 'pqfw' ); ?></th>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Price', 'pqfw' ); ?></th>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Quantity', 'pqfw' ); ?></th>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Message', 'pqfw' ); ?></th>
			</tr>
		</thead>
		<tbody class="pqfw-list-products-body">
			<?php $quoteProducts = unserialize( get_post_meta( $quotation->ID, 'pqfw_products_info', true ) ); ?>
			<?php if ( is_array( $quoteProducts ) ) : ?>
				<?php foreach ( $quoteProducts as $product ) : ?>
				<tr class="pqfw-list-of-single-product">
					<td class="pqfw-product-thumbnail">
						<?php if ( ! empty( $product['img'] ) ) : ?>
							<a href="<?php echo esc_url( $product['link'] ); ?>" target="_blank">
								<img src="<?php echo esc_url( $product['img'] ); ?>">
							</a>
						<?php endif; ?>
					</td>
					<td>
						<a href="<?php echo esc_url( $product['link'] ); ?>" target="_blank">
							<?php echo esc_html( $product['name'] ); ?>
						</a><br>
						<?php pqfw()->helpers->build_variations( $product['variation_detail'] ); ?>
					</td>
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
					<td><strong><?php echo esc_html( $product['quantity'] ); ?></strong></td>
					<td><?php echo esc_html( $product['message'] ); ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>