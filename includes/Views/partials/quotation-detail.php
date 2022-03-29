<div class="pisol-quotation-detail-container">
	<h2>quotation #<?php echo $quotation->ID; ?> detail</h2>

	<hr>
	<h3><?php esc_html_e( 'Person Details', 'pqfw' ); ?></h3>
	<table class="pi-personal-detail">
		<tr>
			<td><strong><?php esc_html_e( 'Name', 'pqfw' ); ?></strong> : <?php echo esc_html( $quotation->name ); ?></td>
			<td><strong><?php esc_html_e( 'Email', 'pqfw' ); ?></strong> : <a href="mailto:<?php echo esc_attr( $quotation->email ); ?>"><?php echo esc_attr( $quotation->email ); ?></a></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'Phone', 'pqfw' ); ?></strong> : <?php echo esc_html( $quotation->phone ); ?></td>
			<td><strong><?php esc_html_e( 'Subject', 'pqfw' ); ?></strong> : <?php echo esc_html( $quotation->subject ); ?></td>
		</tr>
		<tr>
		<td colspan="2">
			<strong><?php esc_html_e( 'Message', 'pqfw' ); ?></strong> : <?php esc_html_e( $quotation->message ); ?>
		</td>
		</tr>
	</table>

	<hr>

	<h3><?php esc_html_e( 'Product Detail', 'pqfw' ); ?></h3>

	<table class="pi-product-table" cellspacing="0">
		<thead>
			<tr>
				<th class="pqfw-product-thumbnail">Image</th>
				<th>Product</th>
				<th>Price</th>
				<th>Quantity</th>
				<th>Message</th>
			</tr>
		</thead>
		<tbody>
			<?php $quoteProducts = unserialize( get_post_meta( $quotation->ID, 'pqfw_products_info', true ) ); ?>
			<?php if ( is_array( $quoteProducts ) ) : ?>
				<?php foreach ( $quoteProducts as $product ) : ?>
				<tr>
					<td class="pi-thumb-col">
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