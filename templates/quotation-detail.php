<?php
/**
 * Woocommerce cart view template.
 *
 * @since 1.2.0
 * @package Quotify
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

?>
<div class="pqfw-quotation-detail-wrap">
	<ul class="pqfw-list-of-person-detail">
		<li><strong><?php esc_html_e( 'Name', 'quotify' ); ?></strong> <?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_name', true ) ); ?></li>
		<li><strong><?php esc_html_e( 'Email', 'quotify' ); ?></strong> <a href="mailto:<?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_email', true ) ); ?>">
			<?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_email', true ) ); ?></a>
		</li>
		<li><strong><?php esc_html_e( 'Phone', 'quotify' ); ?></strong> <?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_phone', true ) ); ?></li>
		<li><strong><?php esc_html_e( 'Subject', 'quotify' ); ?></strong> <?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_subject', true ) ); ?></li>
		<li><strong><?php esc_html_e( 'Message', 'quotify' ); ?></strong> <?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_comments', true ) ); ?></li>
	</ul>
</div>