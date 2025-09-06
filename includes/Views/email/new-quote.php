<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( $collection['email_title'] ); ?> - Customer Quotation</title>
	<style>
		img {
			border: none;
			-ms-interpolation-mode: bicubic;
			max-width: 100%;
		}

		body {
			background-color: #f6f6f6;
			width: 100%;
			font-family: sans-serif;
			-webkit-font-smoothing: antialiased;
			font-size: 14px;
			line-height: 1.4;
			margin: 0;
			padding: 0;
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
		.container {
			display: block;
			margin: 0 auto !important;
			/* makes it centered */
			max-width: 580px;
			padding: 10px;
			width: 580px;
			background: white;
		}

		/* This should also be a block element, so that it will fill 100% of the .container */
		.content {
			box-sizing: border-box;
			display: block;
			margin: 0 auto;
			max-width: 580px;
			padding: 45px;
		}

		/* HEADER, FOOTER, MAIN */
		.main {
			background: #ffffff;
			border-radius: 3px;
			width: 100%;
		}

		.wrapper {
			box-sizing: border-box;
		}

		.wrapper p {
			font-size: 14px;
			margin-bottom: 10px;
		}

		.wrapper .entry-button {
			display: flex;
			column-gap: 15px;
		}

		.content-block {
			padding-bottom: 10px;
			padding-top: 10px;
		}

		.footer {
			clear: both;
			margin-top: 10px;
			width: 100%;
			margin-top: 30px;
			font-size: 15px;
		}

		.footer p,
		.footer span,
		.footer a {
			color: #999999;
		}

		/* TYPOGRAPHY */
		h1,
		h2,
		h3,
		h4,
		h5,
		h6 {
			color: #000000;
			font-family: sans-serif;
			font-weight: 500;
			line-height: 1.4;
			margin: 0;
			margin-bottom: 10px;
		}

		h1 {
			font-size: 35px;
		}

		p,
		ul,
		ol {
			font-family: sans-serif;
			font-size: 14px;
			font-weight: normal;
			margin: 0;
			margin-bottom: 15px;
		}

		p li,
		ul li,
		ol li {
			list-style-position: inside;
			margin-left: 5px;
		}

		a {
			color: #3498db;
			text-decoration: underline;
		}

		/* BUTTONS */

		.btn-primary,
		.btn-secondary {
			box-sizing: border-box;
			display: inline-block;
			text-decoration: none;
			font-size: 14px;
			padding: 12px 30px;
			background: #7B68EE;
			color: #FFFFFF !important;
			border-radius: 6px;
			font-weight: normal;
		}

		.btn-secondary {
			background: #EAEBEE;
			color: #0A083A !important;
		}

		h5.main-heading {
			font-size: 21px;
			margin-bottom: 25px;
		}

		/* OTHER STYLES THAT MIGHT BE USEFUL */
		.last {
			margin-bottom: 0;
		}

		.first {
			margin-top: 0;
		}

		.align-center {
			text-align: center;
		}

		.align-right {
			text-align: right;
		}

		.align-left {
			text-align: left;
		}

		.clear {
			clear: both;
		}

		.mt0 {
			margin-top: 0;
		}

		.mb0 {
			margin-bottom: 0;
		}

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

		.powered-by a {
			text-decoration: none;
		}

		hr {
			border: 0;
			border-bottom: 1px solid #f6f6f6;
			margin: 20px 0;
		}

		/* Additional styles for the template */
		.customer-info {
			background-color: #f8f9fa;
			padding: 20px;
			border-radius: 5px;
			margin-bottom: 20px;
		}
		
		.product-grid {
			display: grid;
			grid-template-columns: 100px 1fr;
			gap: 15px;
			margin-bottom: 20px;
			padding: 15px;
			border: 1px solid #eaeaea;
			border-radius: 5px;
		}
		
		.product-image {
			grid-row: span 4;
			align-self: start;
		}
		
		.product-title {
			font-weight: bold;
			color: #2c3e50;
			margin-bottom: 5px;
		}
		
		.product-detail {
			margin-bottom: 5px;
			color: #7f8c8d;
		}
		
		.section-heading {
			color: #7B68EE;
			border-bottom: 2px solid #7B68EE;
			padding-bottom: 10px;
			margin-top: 30px;
			margin-bottom: 20px;
		}
	</style>
</head>

<body>
	<div class="preheader"><?php echo esc_html( $collection['email_title'] ); ?> inquiry from your website</div>
	<div class="container">
		<div class="content">
			<table role="presentation" class="main">
				<tr>
					<td class="wrapper">
						<h5 class="main-heading align-center">New Customer Inquiry</h5>
						
						<div class="customer-info">
							<h4 class="section-heading">Customer Information</h4>
							<p><strong>Name:</strong> <?php echo esc_attr( $collection['fullname'] ); ?></p>
							<p><strong>Email:</strong> <?php echo esc_attr( $collection['email'] ); ?></p>
							<p><strong>Phone:</strong> <?php echo esc_attr( $collection['phone'] ); ?></p>
							<p><strong>Questions or comments:</strong> <?php echo esc_textarea( $collection['comments'] ); ?></p>
						</div>
						
						<h4 class="section-heading">Requested Products</h4>
						
						<?php if ( $products ) : ?>
							<?php foreach ( $products as $product ) : ?>
							<div class="product-grid">
								<div class="product-image">
									<a href="#">
										<img src="<?php echo esc_url( $product['img'] ); ?>" alt="<?php echo esc_attr( $product['name'] ); ?>" style="display: block" height="100" width="100" />
									</a>
								</div>
								<div class="product-title"><?php echo esc_attr( $product['name'] ); ?></div>
								<div class="product-detail"><strong>Quantity:</strong> <?php echo esc_attr( $product['quantity'] ); ?></div>
								<div class="product-detail"><strong>Price:</strong> <?php echo esc_attr( $product['price'] ); ?></div>
								<div class="product-detail"><strong>Note:</strong> <?php echo esc_textarea( $product['message'] ); ?></div>
							</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
				</tr>
			</table>
			
			<div class="footer">
				<table role="presentation" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="content-block">
							<span class="powered-by">
								This email is automatically generated from: <a href="<?php echo esc_url( $collection['site_url'] ); ?>">
									<?php echo esc_attr( $collection['email_title'] ); ?>
								</a>
							</span>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
