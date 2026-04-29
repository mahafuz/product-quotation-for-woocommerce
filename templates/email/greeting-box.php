<?php
/**
 * Greeting Box Template Part
 *
 * Creates a colorful greeting section for customer-facing emails.
 *
 * @since 2.6.0
 * @package Quotify
 *
 * @param array $args {
 *     Greeting arguments.
 *
 *     @type string $heading     Main heading text.
 *     @type string $message     Subtitle/message text.
 *     @type string $gradient    Optional. Custom gradient CSS.
 * }
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$defaults = [
	'heading'  => __( 'Thank You!', 'quotify' ),
	'message'  => '',
	'gradient' => 'linear-gradient(135deg, #7B68EE 0%, #9B59B6 100%)',
];

$args = wp_parse_args( $args, $defaults );
?>
<div class="greeting-box" style="background: <?php echo esc_attr( $args['gradient'] ); ?>; color: white; padding: 30px; border-radius: 8px; text-align: center; margin-bottom: 30px;">
	<h2 style="color: white; margin-bottom: 15px;"><?php echo esc_html( $args['heading'] ); ?></h2>
	<?php if ( ! empty( $args['message'] ) ) : ?>
		<p style="color: rgba(255, 255, 255, 0.9); font-size: 16px; margin-bottom: 0;">
			<?php echo esc_html( $args['message'] ); ?>
		</p>
	<?php endif; ?>
</div>
