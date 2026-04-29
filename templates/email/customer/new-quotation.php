<?php
/**
 * Customer Email Template - Quotation Confirmation
 *
 * @since 2.6.0
 * @package Quotify
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

// Load email helper functions.
require_once QUOTIFY_PLUGIN_VIEWS . 'email/email-functions.php';

// Normalize data.
$data = quotify_get_email_data( get_defined_vars() );

// Header args.
$header_args = [
	'title'       => sprintf(
		/* translators: %s: Site name */
		__( '%s - Thank You for Your Inquiry', 'quotify' ),
		$data['email_title']
	),
	'heading'     => '', // No heading, using greeting box instead.
	'description' => '',
];

// Load header.
quotify_get_email_template_part( 'header-default', null, [ 'args' => $header_args ] );
?>

<div class="greeting-box">
	<h2><?php echo esc_html( quotify_get_custom_email_message( 'customer', 'greeting', __( 'Thank You for Your Inquiry!', 'quotify' ) ) ); ?></h2>
	<p><?php esc_html_e( 'We\'ve received your quotation request and will get back to you shortly.', 'quotify' ); ?></p>
</div>

<p>
	<?php
	// translators: %s: Customer name.
	printf( esc_html__( 'Dear %s,', 'quotify' ), '<strong>' . esc_html( $data['fullname'] ) . '</strong>' );
	?>
</p>

<p>
	<?php
	$default_intro = __( 'Thank you for your interest in our products. We have successfully received your quotation request and our team is reviewing it. You can expect to hear from us within 1-2 business days with a detailed quotation.', 'quotify' ); // phpcs:ignore Generic.Files.LineLength.MaxExceeded
	echo wp_kses_post( quotify_get_custom_email_message( 'customer', 'intro', $default_intro ) );
	?>
</p>

<?php if ( ! empty( $data['quotation_id'] ) || ! empty( $data['date'] ) ) : ?>
	<div class="box-notice">
		<?php if ( ! empty( $data['quotation_id'] ) ) : ?>
			<p>
				<strong>📋 <?php esc_html_e( 'Quotation Reference:', 'quotify' ); ?></strong>
				#<?php echo esc_html( $data['quotation_id'] ); ?>
			</p>
		<?php endif; ?>
		<?php if ( ! empty( $data['date'] ) ) : ?>
			<p>
				<strong>📅 <?php esc_html_e( 'Submitted on:', 'quotify' ); ?></strong>
				<?php echo esc_html( $data['date'] ); ?>
			</p>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php
// Product list - simple for customer.
quotify_get_email_template_part( 'product-list-simple', null, [
	'products' => $data['products'],
	'heading'  => __( 'Your Request Summary', 'quotify' ),
] );
?>

<h4 class="section-heading"><?php esc_html_e( 'What Happens Next?', 'quotify' ); ?></h4>
<?php
	$default_what_next = __( "Our team reviews your product inquiry and requirements\nWe prepare a customized quotation with pricing details\nYou'll receive an email with your quotation and next steps\nIf you have questions, feel free to contact us anytime", 'quotify' ); // phpcs:ignore Generic.Files.LineLength.MaxExceeded
	$what_next = quotify_get_custom_email_message( 'customer', 'what_next', $default_what_next );
	$what_next_lines = explode( "\n", $what_next );
if ( ! empty( $what_next_lines ) ) :
	?>
	<ul style="color: #333333;">
	<?php foreach ( $what_next_lines as $line ) : ?>
			<li><?php echo esc_html( $line ); ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<div class="box-contact">
	<h4><?php esc_html_e( 'Need to Get in Touch?', 'quotify' ); ?></h4>
	<p><?php esc_html_e( 'Have questions or want to modify your inquiry? We\'re here to help!', 'quotify' ); ?></p>
	<p>
		<strong><?php esc_html_e( 'Email:', 'quotify' ); ?></strong>
		<a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
			<?php echo esc_html( get_option( 'admin_email' ) ); ?>
		</a>
	</p>
	<p>
		<strong><?php esc_html_e( 'Website:', 'quotify' ); ?></strong>
		<a href="<?php echo esc_url( $data['site_url'] ); ?>"><?php echo esc_html( $data['email_title'] ); ?></a>
	</p>
</div>

<p style="text-align: center; margin-top: 30px;">
	<em><?php echo esc_html( quotify_get_custom_email_message( 'customer', 'closing', __( 'We appreciate your business and look forward to serving you!', 'quotify' ) ) ); ?></em>
</p>

<?php
// Footer args.
$footer_args = [
	'site_name' => $data['email_title'],
	'site_url'  => $data['site_url'],
];

// Load footer.
quotify_get_email_template_part( 'footer-default', null, [ 'args' => $footer_args ] );
