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

				<th class="pqfw-list-products-head"><?php esc_html_e( 'Price', 'pqfw' ); ?></th>

				<th class="pqfw-list-products-head"><?php esc_html_e( 'Quantity', 'pqfw' ); ?></th>

				<th class="pqfw-list-products-head"><?php esc_html_e( 'Message', 'pqfw' ); ?></th>
			</tr>
		</thead>
		<tbody class="pqfw-list-products-body">
			<?php
				$quoteProducts = get_post_meta( $quotation->ID, 'pqfw_products_info', true );
				$quoteProducts = ! is_array( $quoteProducts ) ? unserialize( $quoteProducts ) : $quoteProducts;
			?>
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
					</td>

					<td>
						<span class="pqfw-cart-price-inc-tax">
							<input
								class="pqfw-quote-text-input pqfw-product-regular-price"
								type="text"
								name="products[<?php echo $key; ?>][regular_price]"
								id="regular_price"
								value="<?php echo isset( $product['regular_price'] ) ? esc_attr( number_format( intval( $product['regular_price'] ) ), 2 ) : ''; ?>"
							>
						</span>
					</td>

					<td>
						<input
							data-price-inc-tax="<?php echo number_format( isset( $product['regular_price'] ) 
								? esc_attr( intval( trim( $product['regular_price'], '$' ) ) / intval( $product['quantity'] ) ) : esc_attr( intval( trim( $product['price'], '$' ) ) / intval( $product['quantity'] ) ?? '' ), 2 ); ?>"
							data-exc-tax-price="<?php echo isset( $product['exc_tax_price'] ) 
							? esc_attr( $product['exc_tax_price'] ) : esc_attr( $product['price'] ?? '' ); ?>"
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
	
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2">
						<div class="pqfw-display-total">
							<h3><?php echo esc_attr( 'Quote Totals', 'pqfw' ); ?></h3>
							<div class="pqfw-subtotal">
								<div class="pqfw-label">
									<span>Subtotal Price: </span>
								</div>
								<div class="pqfw-input">
									<span id="display-total-price-inc-tax">$100</span>
									<input type="hidden" name="products[<?php echo $key; ?>][total_price_inc_tax]" id="total_price_inc_tax" value="" />
								</div>
							</div>

							<div class="tax">
								<div class="pqfw-label">
									<span>Tax Rate (%)</span>
									<span class="edit-tax">edit</span>

									<div class="pqfw-tax-add">
										<input type="text" name="pqfw_quote_tax" id="pqfw_quote_tax">
										<button class="button" type="button">Apply</button>
									</div>
								</div>
								<div class="pqfw-input">
									<span id="display-total-tax">$0</span>
									<input type="hidden" name="products[<?php echo $key; ?>][total_tax]" id="total_tax" value="" />
								</div>
							</div>

							<div class="discount">
								<div class="pqfw-label">
									<span>Discount</span>
									<span class="edit-discount">edit</span>

									<div class="pqfw-discount-add">
										<input type="text" name="pqfw_quote_discount" id="pqfw_quote_discount">
										<button class="button" type="button">Apply</button>

										<div class="discount-type-wrapper">
											<ul class="pqfw_quote_discount">
												<li>
													<input
														type="radio"
														class="pqfw_quote_discount"
														name="pqfw_quote_discount_type"
														id="pqfw_quote_discount_type1"
														value="amount"
														checked="checked"
													>
													<label for="pqfw_quote_discount_type1">Fixed Amount</label>
												</li>
												<li>
													<input
														type="radio"
														class="pqfw_quote_discount"
														name="pqfw_quote_discount_type"
														id="pqfw_quote_discount_type2"
														value="percentage"
													>
													<label for="pqfw_quote_discount_type2">Percentage</label>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="pqfw-input">
									<span id="display-total-discount">$0</span>
									<input type="hidden" name="products[<?php echo $key; ?>][total_discount]" id="total_discount" value="" />
								</div>
							</div>

							<div class="grand-total">
								<div class="pqfw-label">
									<span>Total Price: </span>
								</div>
								<div class="pqfw-input">
									<span id="display-total-price-inc-tax">$100</span>
									<input type="hidden" name="products[<?php echo $key; ?>][total_price_inc_tax]" id="total_price_inc_tax" value="" />
								</div>
							</div>
						</div>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
