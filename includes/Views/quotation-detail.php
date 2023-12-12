<?php
/**
 * Displays quotaion detail meta box.
 *
 * @since 1.2.0
 * @package PQFW
 */

$data = get_post_meta( $quotation->ID );
unset( $data['pqfw_products_info'] );
unset( $data['pqfw_products_ids'] );
unset( $data['_edit_lock'] );
?>
<div class="pqfw-quotation-detail-wrap">
	<ul class="pqfw-list-of-person-detail">
	<?php
		// phpcs:disable
		foreach ( $data as $key => $item ) {

			if ( empty( $item[0] ) ) {
				continue;
			}

			if ( 'pqfw_customer_name' === $key ) {
				echo '<li><strong>' . esc_html__( 'Name', 'pqfw' ) . '</strong> ' . esc_html( $item[0] ) . '</li>';
			}

			if ( 'pqfw_customer_email' === $key ) {
				echo '<li><strong>' . esc_html__( 'Email', 'pqfw' ) . '</strong> <a href="mailto:' . esc_html( $item[0] ) . '">
					' . esc_html( $item[0] ) . '</a></li>';
			}

			if ( 'pqfw_customer_phone' === $key ) {
				echo '<li><strong>' . esc_html__( 'Phone', 'pqfw' ) . '</strong> ' . esc_html( $item[0] ) . '</li>';
			}

			if ( 'pqfw_subject' === $key ) {
				echo '<li><strong>' . esc_html__( 'Subject', 'pqfw' ) . '</strong> ' . esc_html( $item[0] ) . '</li>';
			}

			if ( 'pqfw_customer_comments' === $key ) {
				echo '<li><strong>' . esc_html__( 'Message', 'pqfw' ) . '</strong> ' . esc_html( $item[0] ) . '</li>';
			}

			if ( 'pqfw_website_url' === $key ) {
				echo '<li><strong>' . esc_html__( 'Website URL', 'pqfw' ) . '</strong> ' . esc_html( $item[0] ) . '</li>';
			}
		}
		// phpcs:enable
	?>
	</ul>
</div>