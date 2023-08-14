<?php
/**
 * Displays quotaion products details.
 *
 * @since 1.2.0
 * @package PQFW
 */

$table_columns = pqfw()->settings->get( 'cart_table_columns' );
$table_columns = wp_list_pluck( $table_columns, 'value' );
$tax_enabled   = wc_tax_enabled();
?>
<div class="pqfw-quotation-products-detail">
	<table class="pqfw-list-products widefat fixed striped table-view-list">
		<thead>
			<tr>
				<th class="pqfw-product-thumbnail pqfw-list-products-head"><?php esc_html_e( 'Image', 'pqfw' ); ?></th>

				<th class="pqfw-list-products-head"><?php esc_html_e( 'Product', 'pqfw' ); ?></th>

				<?php if ( $tax_enabled ) : ?>
					<th class="pqfw-list-products-head"><?php esc_html_e( 'Price Excluding Tax.', 'pqfw' ); ?></th>
				<?php endif; ?>
				<th class="pqfw-list-products-head"><?php esc_html_e( 'Price', 'pqfw' ); ?></th>

				<th class="pqfw-list-products-head"><?php esc_html_e( 'Quantity', 'pqfw' ); ?></th>

				<th class="pqfw-list-products-head"><?php esc_html_e( 'Message', 'pqfw' ); ?></th>
			</tr>
		</thead>
		<tbody class="pqfw-list-products-body">
			<?php $quoteProducts = unserialize( get_post_meta( $quotation->ID, 'pqfw_products_info', true ) ); ?>
			<?php if ( is_array( $quoteProducts ) ) : ?>
				<?php
					foreach ( $quoteProducts as $key => $product ) :
					$key = absint( $key );
				?>
				<tr class="pqfw-list-of-single-product">

					<td class="pqfw-product-thumbnail">
						<?php if ( ! empty( $product['img'] ) ) : ?>
							<a href="<?php echo esc_url( $product['link'] ); ?>" target="_blank">
								<input type="hidden" name="products[<?php echo $key; ?>][img]" value="<?php echo esc_url( $product['img'] ); ?>" />
								<img src="<?php echo esc_url( $product['img'] ); ?>">
							</a>
						<?php endif; ?>
					</td>

					<td>
						<a href="<?php echo esc_url( $product['link'] ); ?>" target="_blank">
							<input type="hidden" name="products[<?php echo $key; ?>][name]" value="<?php echo esc_html( $product['name'] ); ?>" />
							<input type="hidden" name="products[<?php echo $key; ?>][link]" value="<?php echo esc_html( $product['link'] ); ?>" />
							<?php echo esc_html( $product['name'] ); ?>
						</a>
						<br>
						<?php // pqfw()->helpers->build_variations( $product['variation_detail'] ); ?>
					</td>

					<?php if ( $tax_enabled ) : ?>
						<td>
							<?php if ( ! empty( $product['exc_tax_price'] ) ) : ?>
							<span class="pqfw-cart-price-exc-tax">
								<input
									type="text"
									class="pqfw-quote-text-input pqfw-product-price-exc-tax"
									name="products[<?php echo $key; ?>][exc_tax_price]"
									id="exc_tax_price"
									value="<?php echo esc_attr( $product['exc_tax_price'] ); ?>"
								>
							</span>
						<?php endif; ?>
						</td>
					<?php endif; ?>

					<td>
						<span class="pqfw-cart-price-inc-tax">
							<input
								class="pqfw-quote-text-input pqfw-product-regular-price"
								type="text"
								name="products[<?php echo $key; ?>][regular_price]"
								id="regular_price"
								value="<?php echo isset( $product['regular_price'] ) ? esc_attr( $product['regular_price'] ) : ''; ?>"
							>
						</span>
					</td>

					<td>
						<input
							data-price-inc-tax="<?php echo isset( $product['regular_price'] ) ? esc_attr( $product['regular_price'] ) : ''; ?>"
							data-exc-tax-price="<?php echo isset( $product['exc_tax_price'] ) ? esc_attr( $product['exc_tax_price'] ) : ''; ?>"
							type="number"
							class="pqfw-quote-text-input pqfw-quote-edit-quantity"
							name="products[<?php echo $key; ?>][quantity]"
							id="quantity"
							value="<?php echo esc_html( $product['quantity'] ); ?>"
						>
					</td>
					<td><textarea name="products[<?php echo $key; ?>][message]" class="pqfw-quote-text-input"><?php echo esc_html( $product['message'] ); ?></textarea></td>
				</tr>
				<?php endforeach; ?>

				<?php if ( $tax_enabled ) : ?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="display-total">
						<span>Total Price: </span>
						<span id="display-total-price-exc-tax">$130</span>
						<input type="hidden" name="products[<?php echo $key; ?>][total_price_exc_tax]" id="total_price_exc_tax" value="" />
					</td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="display-total" style="border-top: 1px solid #e5e5e5;">
						<span>Total price including tax: </br>
							<a
								style="font-size: 10px; font-style: italic; text-decoration: underline;" target="_blank"
								rel="nofollow"
								href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=tax&section=standard' ) ); ?>"
							>
								see tax rates.
							</a>
						</span>
						<span id="display-total-price-inc-tax">$100</span>
						<input type="hidden" name="products[<?php echo $key; ?>][total_price_inc_tax]" id="total_price_inc_tax" value="" />
					</td>
				</tr>
				<?php else : ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<?php if ( $tax_enabled ) : ?>
							<td></td>
						<?php endif; ?>
						<td class="display-total">
							<span>Total Price: </span>
							<span id="display-total-price-inc-tax">$100</span>
							<input type="hidden" name="products[<?php echo $key; ?>][total_price_inc_tax]" id="total_price_inc_tax" value="" />
						</td>
					</tr>
				<?php endif; ?>	
			<?php endif; ?>
		</tbody>
	</table>
</div>
