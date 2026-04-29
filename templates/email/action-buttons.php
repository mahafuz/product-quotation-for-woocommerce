<?php
/**
 * Action Buttons Template Part
 *
 * Creates action buttons for emails (e.g., View Quotation, Accept, Reject).
 *
 * @since 2.6.0
 * @package Quotify
 *
 * @param array $args {
 *     Button arguments.
 *
 *     @type array  $buttons     Array of button configs.
 *     @type string $align       Alignment (left, center, right).
 *     @type string $box_style   Box style wrapper (actions, none).
 * }
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$defaults = [
	'buttons'   => [],
	'align'     => 'center',
	'box_style' => 'actions',
];

$args = wp_parse_args( $args, $defaults );

if ( empty( $args['buttons'] ) ) {
	return;
}

$button_defaults = [
	'text'   => '',
	'url'    => '#',
	'style'  => 'primary',
	'target' => '',
];

$box_classes = [
	'actions' => 'box-actions',
	'none'    => '',
];

$box_class = isset( $box_classes[ $args['box_style'] ] ) ? $box_classes[ $args['box_style'] ] : '';
$align_style = 'text-align: ' . esc_attr( $args['align'] ) . ';';
?>

<?php if ( ! empty( $box_class ) ) : ?>
	<div class="<?php echo esc_attr( $box_class ); ?>" style="<?php echo esc_attr( $align_style ); ?>">
<?php else : ?>
	<div style="<?php echo esc_attr( $align_style ); ?> margin: 20px 0;">
<?php endif; ?>

	<?php if ( count( $args['buttons'] ) > 1 ) : ?>
		<p><strong><?php esc_html_e( 'Quick Actions:', 'quotify' ); ?></strong></p>
	<?php endif; ?>

	<?php foreach ( $args['buttons'] as $button ) : ?>
		<?php
		$button = wp_parse_args( $button, $button_defaults );

		if ( empty( $button['text'] ) ) {
			continue;
		}

		$btn_class = 'primary' === $button['style'] ? 'btn-primary' : 'btn-secondary';
		$target_attr = ! empty( $button['target'] ) ? ' target="' . esc_attr( $button['target'] ) . '"' : '';
		?>

		<a href="<?php echo esc_url( $button['url'] ); ?>"
		   class="<?php echo esc_attr( $btn_class ); ?>"
		   style="display: inline-block; margin: 5px;"
		  <?php echo $target_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php echo esc_html( $button['text'] ); ?>
		</a>
	<?php endforeach; ?>
</div>

<?php
/**
 * Fires after action buttons.
 *
 * @since 2.6.0
 *
 * @param array $args Button arguments.
 */
do_action( 'quotify_email_after_action_buttons', $args );
