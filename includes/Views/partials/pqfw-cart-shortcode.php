<div class="woocommerce">
<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
    <thead>
        <tr>
            <th class="product-remove">&nbsp;</th>
            <th class="product-thumbnail">&nbsp;</th>
            <th class="product-name">
                <?php esc_html_e( 'Product', 'woocommerce' ); ?>
            </th>
            <th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
            <th class="product-quantity">
                <?php esc_html_e( 'Quantity', 'woocommerce' ); ?>
            </th>
            <th class="product-subtotal">
                <?php esc_html_e( 'Message', 'woocommerce' ); ?>
            </th>
        </tr>
    </thead>
    <tbody id="pqfw-enquiry-list-row"></tbody>
</table>

<!-- Put form here. -->
<?php pqfw()->form->form(); ?>