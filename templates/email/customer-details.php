<?php
/**
 * Customer Details Template Part
 *
 * @since 2.6.0
 * @package Quotify
 *
 * @param array $data {
 *     Customer data.
 *
 *     @type string $fullname  Customer name.
 *     @type string $email     Customer email.
 *     @type string $phone     Customer phone.
 *     @type string $comments  Customer comments/questions.
 *     @type string $style     Box style (info, highlight, notice).
 * }
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$defaults = [
	'fullname' => '',
	'email'    => '',
	'phone'    => '',
	'comments' => '',
	'style'    => 'highlight',
];

$data = wp_parse_args( $data, $defaults );

$style_classes = [
	'info'      => 'box-info',
	'highlight' => 'box-highlight',
	'notice'    => 'box-notice',
];

$box_class = isset( $style_classes[ $data['style'] ] ) ? $style_classes[ $data['style'] ] : 'box-info';
?>

<div class="<?php echo esc_attr( $box_class ); ?>">
	<h4 class="section-heading" style="margin-top: 0; border-bottom: none; padding-bottom: 5px;">
		<?php esc_html_e( 'Customer Information', 'quotify' ); ?>
	</h4>

	<?php if ( ! empty( $data['fullname'] ) ) : ?>
		<p>
			<strong><?php esc_html_e( 'Name:', 'quotify' ); ?></strong>
			<?php echo esc_html( $data['fullname'] ); ?>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $data['email'] ) ) : ?>
		<p>
			<strong><?php esc_html_e( 'Email:', 'quotify' ); ?></strong>
			<a href="mailto:<?php echo esc_attr( $data['email'] ); ?>"><?php echo esc_html( $data['email'] ); ?></a>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $data['phone'] ) ) : ?>
		<p>
			<strong><?php esc_html_e( 'Phone:', 'quotify' ); ?></strong>
			<?php echo esc_html( $data['phone'] ); ?>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $data['comments'] ) ) : ?>
		<p>
			<strong><?php esc_html_e( 'Questions/Comments:', 'quotify' ); ?></strong>
		</p>
		<p><?php echo esc_html( $data['comments'] ); ?></p>
	<?php endif; ?>
</div>

<?php
/**
 * Fires after customer details section.
 *
 * @since 2.6.0
 *
 * @param array $data Customer data.
 */
do_action( 'quotify_email_after_customer_details', $data );
