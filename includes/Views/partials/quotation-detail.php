<?php
/**
 * Displays quotaion detail meta box.
 *
 * @since 1.2.0
 * @package PQFW
 */

?>
<div class="pqfw-quotation-detail-wrap">
	<h3><?php esc_html_e( 'Person Details', 'pqfw' ); ?></h3>

	<ul class="pqfw-list-of-person-detail">
		<li><strong><?php esc_html_e( 'Name', 'pqfw' ); ?></strong> : <?php echo esc_html( $quotation->name ); ?></li>
		<li><strong><?php esc_html_e( 'Email', 'pqfw' ); ?></strong> : <a href="mailto:<?php echo esc_attr( $quotation->email ); ?>"><?php echo esc_attr( $quotation->email ); ?></a></li>
		<li><strong><?php esc_html_e( 'Phone', 'pqfw' ); ?></strong> : <?php echo esc_html( $quotation->phone ); ?></li>
		<li><strong><?php esc_html_e( 'Subject', 'pqfw' ); ?></strong> : <?php echo esc_html( $quotation->subject ); ?></li>
		<li><strong><?php esc_html_e( 'Message', 'pqfw' ); ?></strong> : <?php echo esc_html( $quotation->message ); ?></li>
	</ul>

	<hr>

	<h3><?php esc_html_e( 'Product Detail', 'pqfw' ); ?></h3>

	<table class="pqfw-list-products">
		<thead>
			<tr>
				<th class="pqfw-product-thumbnail pqfw-list-products-head">Image</th>
				<th class="pqfw-list-products-head">Product</th>
				<th class="pqfw-list-products-head">Price</th>
				<th class="pqfw-list-products-head">Quantity</th>
				<th class="pqfw-list-products-head">Message</th>
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
								<img class="pi-thumb" src="<?php echo esc_url( $product['img'] ); ?>">
							</a>
						<?php endif; ?> 
					</td>
					<td>
						<a href="<?php echo esc_url( $product['link'] ); ?>" target="_blank">
							<?php echo esc_html( $product['name'] ); ?>
						</a><br>
						<?php $this->buildVariations( $product['variation_detail'] ); ?>
					</td>
					<td><strong><?php echo esc_html( $product['price'] ); ?></strong></td>
					<td><strong><?php echo esc_html( $product['quantity'] ); ?></strong></td>
					<td><?php echo esc_html( $product['message'] ); ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>