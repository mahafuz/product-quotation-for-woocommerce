<?php
/**
 * Email Header Template Part
 *
 * @since 2.6.0
 * @package Quotify
 *
 * @param array $args {
 *     Header arguments.
 *
 *     @type string $title       Email title.
 *     @type string $heading     Main heading text.
 *     @type string $description Optional description text.
 * }
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$defaults = [
	'title'       => get_bloginfo( 'name' ),
	'heading'     => '',
	'description' => '',
];

$args = wp_parse_args( $args, $defaults );
$styles = quotify_get_email_styles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( $args['title'] ); ?></title>
	<style>
		img {
			border: none;
			-ms-interpolation-mode: bicubic;
			max-width: 100%;
		}

		body {
			<?php echo esc_attr( $styles['body'] ); ?>
		}

		.container {
			<?php echo esc_attr( $styles['container'] ); ?>
		}

		.content {
			<?php echo esc_attr( $styles['content'] ); ?>
		}

		.main {
			<?php echo esc_attr( $styles['main'] ); ?>
		}

		.wrapper {
			<?php echo esc_attr( $styles['wrapper'] ); ?>
		}

		h1, h2, h3, h4, h5, h6 {
			<?php echo esc_attr( $styles['heading_primary'] ); ?>
		}

		h2 {
			<?php echo esc_attr( $styles['heading_secondary'] ); ?>
		}

		p {
			<?php echo esc_attr( $styles['text'] ); ?>
		}

		a {
			<?php echo esc_attr( $styles['link'] ); ?>
		}

		.btn-primary {
			<?php echo esc_attr( $styles['button_primary'] ); ?>
		}

		.btn-secondary {
			<?php echo esc_attr( $styles['button_secondary'] ); ?>
		}

		.section-heading {
			<?php echo esc_attr( $styles['section_heading'] ); ?>
		}

		.box-info {
			<?php echo esc_attr( $styles['box_info'] ); ?>
		}

		.box-highlight {
			<?php echo esc_attr( $styles['box_highlight'] ); ?>
		}

		.align-center {
			<?php echo esc_attr( $styles['align_center'] ); ?>
		}

		hr {
			<?php echo esc_attr( $styles['hr'] ); ?>
		}

		/* Product grid styles */
		.product-grid {
			display: grid;
			grid-template-columns: 100px 1fr;
			gap: 15px;
			margin-bottom: 20px;
			padding: 15px;
			border: 1px solid #eaeaea;
			border-radius: 5px;
			background-color: #ffffff;
		}

		.product-image {
			grid-row: span 4;
			align-self: start;
		}

		.product-image img {
			display: block;
			border: 1px solid #eaeaea;
			border-radius: 3px;
			height: 100px;
			width: 100px;
			object-fit: cover;
		}

		.product-title {
			font-weight: bold;
			color: #2c3e50;
			margin-bottom: 5px;
			font-size: 16px;
		}

		.product-detail {
			margin-bottom: 5px;
			color: #7f8c8d;
			font-size: 14px;
		}

		.product-price {
			font-weight: 600;
			color: #27ae60;
		}

		/* Product list styles (simplified) */
		.product-item {
			padding: 12px 0;
			border-bottom: 1px solid #eaeaea;
		}

		.product-item:last-child {
			border-bottom: none;
		}

		.product-name {
			font-weight: 600;
			color: #2c3e50;
			font-size: 15px;
		}

		.product-quantity {
			color: #7f8c8d;
			font-size: 14px;
		}

		/* Footer styles */
		.footer {
			<?php echo esc_attr( $styles['footer'] ); ?>
		}

		.footer p,
		.footer span,
		.footer a {
			<?php echo esc_attr( $styles['footer_text'] ); ?>
		}

		/* Utility classes */
		.preheader {
			color: transparent;
			display: none;
			height: 0;
			max-height: 0;
			max-width: 0;
			opacity: 0;
			overflow: hidden;
			mso-hide: all;
			visibility: hidden;
			width: 0;
		}

		.meta-info {
			<?php echo esc_attr( $styles['meta_info'] ); ?>
		}

		.meta-info p {
			margin: 3px 0;
			font-size: 12px;
		}

		.box-notice {
			<?php echo esc_attr( $styles['box_notice'] ); ?>
		}

		.box-notice p {
			margin: 5px 0;
			font-size: 13px;
		}

		.box-contact {
			<?php echo esc_attr( $styles['box_contact'] ); ?>
		}

		.box-contact h4 {
			margin-top: 0;
			margin-bottom: 10px;
			color: #2980b9;
		}

		.box-contact p {
			margin: 5px 0;
			font-size: 14px;
		}

		.box-actions {
			<?php echo esc_attr( $styles['box_actions'] ); ?>
		}

		.greeting-box {
			background: linear-gradient(135deg, #7B68EE 0%, #9B59B6 100%);
			color: white;
			padding: 30px;
			border-radius: 8px;
			text-align: center;
			margin-bottom: 30px;
		}

		.greeting-box h2 {
			color: white;
			margin-bottom: 15px;
		}

		.greeting-box p {
			color: rgba(255, 255, 255, 0.9);
			font-size: 16px;
			margin-bottom: 0;
		}

		.product-summary {
			background-color: #f8f9fa;
			padding: 20px;
			border-radius: 5px;
			margin: 20px 0;
		}
	</style>
</head>

<body>
	<div class="preheader"><?php echo esc_html( $args['title'] ); ?></div>
	<div class="container">
		<div class="content">
			<table role="presentation" class="main">
				<tr>
					<td class="wrapper">
						<?php if ( ! empty( $args['heading'] ) ) : ?>
							<h5 class="<?php echo ! empty( $args['align'] ) ? esc_attr( $args['align'] ) : 'align-center'; ?>" style="font-size: 21px; margin-bottom: 25px; color: #7B68EE;">
								<?php echo esc_html( $args['heading'] ); ?>
							</h5>
						<?php endif; ?>

						<?php if ( ! empty( $args['description'] ) ) : ?>
							<p class="align-center"><?php echo esc_html( $args['description'] ); ?></p>
						<?php endif; ?>
