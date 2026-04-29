<?php
/**
 * Meta Information Template Part
 *
 * Displays quotation metadata like ID, date, time, etc.
 *
 * @since 2.6.0
 * @package Quotify
 *
 * @param array $data {
 *     Meta data.
 *
 *     @type int    $quotation_id Quotation ID.
 *     @type string $date         Quotation date.
 *     @type string $time         Quotation time.
 *     @type string $subject      Quotation subject.
 *     @type string $status       Quotation status.
 * }
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$defaults = [
	'quotation_id' => 0,
	'date'         => '',
	'time'         => '',
	'subject'      => '',
	'status'       => '',
];

$data = wp_parse_args( $data, $defaults );

// Only show if we have at least one piece of meta data.
$has_meta = ! empty( $data['quotation_id'] ) || ! empty( $data['date'] ) || ! empty( $data['time'] ) || ! empty( $data['subject'] ) || ! empty( $data['status'] );

if ( ! $has_meta ) {
	return;
}
?>

<div class="meta-info">
	<?php if ( ! empty( $data['quotation_id'] ) ) : ?>
		<p>
			<strong><?php esc_html_e( 'Quotation ID:', 'quotify' ); ?></strong>
			#<?php echo esc_html( $data['quotation_id'] ); ?>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $data['date'] ) ) : ?>
		<p>
			<strong><?php esc_html_e( 'Date:', 'quotify' ); ?></strong>
			<?php echo esc_html( $data['date'] ); ?>
			<?php if ( ! empty( $data['time'] ) ) : ?>
				<?php esc_html_e( 'at', 'quotify' ); ?> <?php echo esc_html( $data['time'] ); ?>
			<?php endif; ?>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $data['subject'] ) ) : ?>
		<p>
			<strong><?php esc_html_e( 'Subject:', 'quotify' ); ?></strong>
			<?php echo esc_html( $data['subject'] ); ?>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $data['status'] ) ) : ?>
		<p>
			<strong><?php esc_html_e( 'Status:', 'quotify' ); ?></strong>
			<?php echo esc_html( $data['status'] ); ?>
		</p>
	<?php endif; ?>
</div>

<?php
/**
 * Fires after meta information section.
 *
 * @since 2.6.0
 *
 * @param array $data Meta data.
 */
do_action( 'quotify_email_after_meta_info', $data );
