<?php
/**
 * Displays quotaion detail meta box.
 *
 * @since 1.2.0
 * @package PQFW
 */
?>
<div class="pqfw-quotation-detail-wrap">
	<ul class="pqfw-list-of-person-detail">
		<li><strong><?php esc_html_e( 'Name', 'pqfw' ); ?></strong> : <?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_name', true ) ); ?></li>
		<li><strong><?php esc_html_e( 'Email', 'pqfw' ); ?></strong> : <a href="mailto:<?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_email', true ) ); ?>">
			<?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_email', true ) ); ?></a>
		</li>
		<li><strong><?php esc_html_e( 'Phone', 'pqfw' ); ?></strong> : <?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_phone', true ) ); ?></li>
		<li><strong><?php esc_html_e( 'Subject', 'pqfw' ); ?></strong> : <?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_subject', true ) ); ?></li>
		<li><strong><?php esc_html_e( 'Message', 'pqfw' ); ?></strong> : <?php echo esc_html( get_post_meta( $quotation->ID, 'pqfw_customer_comments', true ) ); ?></li>
	</ul>
</div>