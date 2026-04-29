<?php
/**
 * Admin Email Template - New Quotation Notification
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
		__( '%s - New Quotation Request', 'quotify' ),
		$data['email_title']
	),
	'heading'     => quotify_get_custom_email_message( 'admin', 'greeting', __( 'New Quotation Request Received', 'quotify' ) ),
	'description' => '',
	'align'       => 'align-center',
];

// Load header.
quotify_get_email_template_part( 'header-default', null, [ 'args' => $header_args ] );
?>

<?php
// Customer details.
quotify_get_email_template_part( 'customer-details', null, [
	'fullname' => $data['fullname'],
	'email'    => $data['email'],
	'phone'    => $data['phone'],
	'comments' => $data['comments'],
	'style'    => 'highlight',
] );
?>

<?php
// Product list - detailed for admin.
quotify_get_email_template_part( 'product-list-detailed', null, [
	'products' => $data['products'],
	'heading'  => __( 'Requested Products', 'quotify' ),
] );
?>

<?php if ( ! empty( $data['admin_edit_url'] ) ) : ?>
	<?php
	// Action buttons.
	quotify_get_email_template_part( 'action-buttons', null, [
		'buttons' => [
			[
				'text'  => __( 'View & Edit Quotation', 'quotify' ),
				'url'   => $data['admin_edit_url'],
				'style' => 'primary',
			],
		],
		'align'     => 'center',
		'box_style' => 'actions',
	] );
	?>
<?php endif; ?>

<?php
// Meta information.
quotify_get_email_template_part( 'meta-info', null, [
	'quotation_id' => $data['quotation_id'],
	'date'         => $data['date'],
	'time'         => $data['time'],
	'subject'      => $data['subject'],
] );
?>

<?php
// Footer args.
$footer_args = [
	'site_name' => $data['email_title'],
	'site_url'  => $data['site_url'],
];

// Load footer.
quotify_get_email_template_part( 'footer-default', null, [ 'args' => $footer_args ] );
