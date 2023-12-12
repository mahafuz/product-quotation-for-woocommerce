<?php
	$table_columns = pqfw()->settings->get( 'cart_table_columns' );
	$table_columns = wp_list_pluck( $table_columns, 'value' );
?>
<div class="woocommerce">
	<form class="woocommerce-cart-form">
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-remove">&nbsp;</th>
					<?php if ( empty( $table_columns ) || in_array( 'thumbnail', $table_columns, true ) ) : ?>
						<th class="product-thumbnail">&nbsp;</th>
					<?php endif; ?>

					<?php if ( empty( $table_columns ) || in_array( 'product', $table_columns, true ) ) : ?>
					<th class="product-name">
						<?php esc_html_e( 'Product', 'woocommerce' ); ?>
					</th>
					<?php endif; ?>

					<?php if ( empty( $table_columns ) || in_array( 'price', $table_columns, true ) ) : ?>
					<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
					<?php endif; ?>

					<?php if ( empty( $table_columns ) || in_array( 'quantity', $table_columns, true ) ) : ?>
					<th class="product-quantity">
						<?php esc_html_e( 'Quantity', 'woocommerce' ); ?>
					</th>
					<?php endif; ?>

					<?php if ( empty( $table_columns ) || in_array( 'message', $table_columns, true ) ) : ?>
					<th class="product-subtotal">
						<?php esc_html_e( 'Message', 'woocommerce' ); ?>
					</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody id="pqfw-quotations-list-row"></tbody>
		</table>

		<!-- Put form here. -->
		<?php pqfw()->form->form(); ?>
	</form>
</div>