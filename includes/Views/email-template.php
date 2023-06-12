<?php
$site_name = esc_attr( get_bloginfo( 'blogname' ) );
$site_url  = esc_url( get_site_url() );

$html = '<!doctype html>
<html lang="en-US">

<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<title>Product Quotation - From ' . $site_name . '</title>
	<meta name="description" content="' . $site_name . ' Email Template">
</head>
<style>
	a:hover {
		text-decoration: underline !important;
	}
</style>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
	<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8" style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;">
		<tr>
			<td>
				<table style="background-color: #f2f3f8; max-width:670px; margin:0 auto;" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td style="height:30px;">&nbsp;</td>
					</tr>
					<tr>
						<td style="height:20px;">&nbsp;</td>
					</tr>
					<tr>
						<td>
							<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:670px; background:#fff; border-radius:3px;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);padding:0 40px;">
								<tr>
									<td style="height:40px;">&nbsp;</td>
								</tr>
								<tr>
									<td style="padding:0 15px; text-align:center;">
										<h1 style="color:#1e1e2d; font-weight:400; margin:0;font-size:32px;font-family:\'Rubik\',sans-serif;">' . $subject . '</h1>
										<span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; 
                                        width:100px;"></span>
									</td>
								</tr>
								<!-- Details Table -->
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" style="width: 100%; border: 1px solid #ededed">
											<tbody>
												<tr>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
														Full Name:</td>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">' . esc_attr( $args['full_name'] ) . '</td>
												</tr>
												 <tr>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
														Email</td>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">' . esc_attr( $args['email'] ) . '</td>
												</tr>
												 <tr>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
														Organization:</td>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">' . esc_attr( $args['organization'] ) . '</td>
												</tr>
												 <tr>
													<td style="padding: 10px; border-bottom: 1px solid #ededed;border-right: 1px solid #ededed; width: 35%; font-weight:500; color:rgba(0,0,0,.64)">
														Address:</td>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056;">' . esc_attr( $args['address'] ) . '</td>
												</tr>
												 <tr>
													<td style="padding: 10px;  border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%;font-weight:500; color:rgba(0,0,0,.64)">
														Phone/Mobile</td>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; color:#455056;">' . esc_attr( $args['phone/mobile'] ) . '</td>
												</tr>
												 <tr>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; border-right: 1px solid #ededed; width: 35%;font-weight:500; color:rgba(0,0,0,.64)">
														Website URL:</td>
													<td style="padding: 10px; border-bottom: 1px solid #ededed; color: #455056; ">
														<a href="' . esc_url( $args['website_url'] ) . '">' . esc_url( $args['website_url'] ) . '</a>
													</td>
												</tr>
												 <tr>
													<td style="padding: 10px; border-right: 1px solid #ededed; width: 35%;font-weight:500; color:rgba(0,0,0,.64)">
														Comments:</td>
													<td style="padding: 10px; color: #455056;">' . wp_kses_post( $args['comments'] ) . '</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:40px;">&nbsp;</td>
								</tr>

								<tr>
									<td style="padding:0 15px;">
										<h3 style="color:#1e1e2d; font-weight:400; margin:0;font-size:23px;font-family:\'Rubik\',sans-serif;">Quotation Products Details:</h3>
										<span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; 
                                        width:100px;"></span>
									</td>
								</tr>
								<!-- Details Table -->
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" style="width: 100%; border: 1px solid #ededed">
											<tbody>';
												$products = pqfw()->quotations->getProducts();
												if ( ! empty( $products ) ) {
													foreach( $products as $product ) {
														$img = wp_get_attachment_image_src( get_post_thumbnail_id( $product['id'] ), 'thumbnail' );
														$img = ! empty( $img[0] ) ? ( $img[0] ) : false;
														$html .= '<tr>
														<td style="padding: 10px; border-right: 1px solid #ededed; border-top: 1px solid #ededed; width: 35%;font-weight:500; color:rgba(0,0,0,.64)">
															<p><a href="' . esc_url( get_permalink( $product['id'] ) ) . '">
															<img src="' . $img . '" alt="' . esc_attr( get_the_title( $product['id'] ) ) . '" title="' . esc_attr( get_the_title( $product['id'] ) ) . '" style="display: block" height="100" width="100" /></a></p>
														</td>
														<td style="padding: 10px; color: #455056; border-top: 1px solid #ededed;">
															<a style="font-size: 18px; color: color: #455056; text-decoration: none;" href="' . get_permalink( $product['id'] ) . '">' . esc_attr( get_the_title( $product['id'] ) ) . '</a>
															<br> <p>Quantity: ' .  absint( $product['quantity'] ) . '
															<br>Price: ' . wc_price( $product['regular_price'] ) . '
															<br>Note: ' . wp_kses_post( $product['message'] ) . '</p>
														</td>
													</tr>';
													}
												}
											$html .= '</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td style="height:40px;">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="height:20px;">&nbsp;</td>
					</tr>
					<tr>
						<td style="text-align:center;">
							<p style="font-size:14px; color:#455056bd; line-height:18px; margin:0 0 0;">&copy; <strong>'.$site_url.'</strong></p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>

</html>';

return $html;