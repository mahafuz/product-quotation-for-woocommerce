<?php

$current_tab  = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'modules';
// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

?>

<style>
#wpcontent {
	padding: 0;
}
#footer-left {
	display: none;
}
.pqfw-settings-wrap {
	margin: 0;
}
.pqfw-settings-wrap * {
	box-sizing: border-box;
}
.pqfw-notices-target {
	margin: 0;
}
.pqfw-settings-header {
	display: flex;
	align-items: center;
	padding: 0 20px;
	background: #fff;
	box-shadow: 0 1px 8px 0 rgba(0,0,0,0.05);
	position: relative;
    height: 50px;
}
.pqfw-settings-header h3 {
	margin: 0;
	font-weight: 500;
}
.pqfw-settings-header h3 .dashicons {
	color: #a2a2a2;
	vertical-align: text-bottom;
}
.pqfw-settings-version {
	position: absolute;
	right: 20px;
}
.pqfw-settings-tabs {
	margin-left: 30px;
}
.pqfw-settings-tabs a,
.pqfw-settings-tabs a:hover,
.pqfw-settings-tabs a.nav-tab-active {
	background: none;
	border: none;
	box-shadow: none;
}
.pqfw-settings-tabs a {
	font-weight: 500;
	padding: 0 10px;
	color: #5f5f5f;
}
.pqfw-settings-tabs a.nav-tab-active {
	color: #333;
}
.pqfw-settings-tabs a > span {
	display: block;
	padding: 10px 0;
	border-bottom: 3px solid transparent;
}
.pqfw-settings-tabs a.nav-tab-active > span {
	border-bottom: 3px solid #0073aa;
}
.pqfw-settings-content {
	padding: 20px;
}
.pqfw-settings-content #pqfw-settings-form {
	background: #fff;
	padding: 10px 30px;
	box-shadow: 1px 1px 10px 0 rgba(0,0,0,0.05);
}
.pqfw-settings-content #pqfw-settings-form .form-table th {
	font-weight: 500;
}
.pqfw-settings-section {
	margin-bottom: 20px;
}
.pqfw-settings-section:after {
	content: "";
	display: table;
	clear: both;
}
.pqfw-settings-section .pqfw-settings-section-title {
	font-weight: 300;
	font-size: 22px;
	border-bottom: 1px solid #eee;
	padding-bottom: 15px;
}
.pqfw-settings-section .pqfw-settings-elements-grid > tbody {
	display: flex;
	align-items: center;
	flex-direction: row;
	flex-wrap: wrap;
}
.pqfw-settings-section .pqfw-settings-elements-grid > tbody tr {
	background: #f3f5f6;
	margin-right: 10px;
	margin-bottom: 10px;
	padding: 12px;
	border-radius: 5px;
}
.pqfw-settings-section .pqfw-settings-elements-grid > tbody tr th,
.pqfw-settings-section .pqfw-settings-elements-grid > tbody tr td {
	padding: 0;
}
.pqfw-settings-section .pqfw-settings-elements-grid th > label {
	user-select: none;
}
.pqfw-settings-section .toggle-all-widgets {
	margin-bottom: 10px;
}
.pqfw-settings-section .pqfw-admin-field-toggle {
	position: relative;
	display: inline-block;
	width: 35px;
	height: 16px;
}
.pqfw-settings-section .pqfw-admin-field-toggle input {
	opacity: 0;
	width: 0;
	height: 0;
}
.pqfw-settings-section .pqfw-admin-field-toggle .pqfw-admin-field-toggle-slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #fff;
	border: 1px solid #7e8993;
	border-radius: 34px;
	-webkit-transition: .4s;
	transition: .4s;
}
.pqfw-settings-section .pqfw-admin-field-toggle .pqfw-admin-field-toggle-slider:before {
	border-radius: 50%;
	position: absolute;
	content: "";
	height: 10px;
	width: 10px;
	left: 2px;
	bottom: 2px;
	background-color: #7e8993;
	-webkit-transition: .4s;
	transition: .4s;
}
.pqfw-settings-section .pqfw-admin-field-toggle input:checked + .pqfw-admin-field-toggle-slider:before {
	background-color: #0071a1;
	-webkit-transform: translateX(19px);
	-ms-transform: translateX(19px);
	transform: translateX(19px);
}
.pqfw-settings-section .pqfw-admin-field-toggle input:focus + .pqfw-admin-field-toggle-slider {
	border-color: #0071a1;
	box-shadow: 0 0 2px 1px #0071a1;
	transition: 0s;
}
.pqfw-settings-form-wrap {
	display: flex;
	flex-direction: row;
	align-items: flex-start;
	flex: 1 1 auto;
}
.pqfw-settings-form-wrap #pqfw-settings-form {
	flex: 2 0 0;
}
.pro-upgrade-banner {
	flex: 1 0 0;
	width: 320px;
	max-width: 320px;
	float: right;
	margin-left: 20px;
	background: #fff;
	border: 1px solid #eee;
	box-shadow: 1px 1px 10px 0 rgba(0,0,0,0.05);
	padding: 20px;
	border-top: 2px solid #5353dc;
}
.pro-upgrade-banner,
.pro-upgrade-banner * {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.pro-upgrade-banner .banner-image {
	text-align: center;
}
.pro-upgrade-banner img {
	max-width: 100%;
	width: 120px;
}
.pro-upgrade-banner h3 {
	font-weight: 400;
	line-height: 1.4;
	margin-bottom: 0;
}
.pro-upgrade-banner .banner-title-1 {
	font-size: 22px;
	font-weight: 300;
	display: none;
}
.pro-upgrade-banner li {
	font-size: 15px;
}
.pro-upgrade-banner li span {
	margin-right: 5px;
}
.pro-upgrade-banner .banner-action {
	text-align: center;
}
.pro-upgrade-banner a.pqfw-button {
	display: inline-block;
	text-align: center;
	margin-top: 10px;
	text-decoration: none;
	background: #5353dc;
	color: #fff;
	padding: 10px 20px;
	border-radius: 50px;
}
.pro-upgrade-banner a.pqfw-button:hover {
	background: #4242ce;
}
</style>

<div class="pqfw-container pqfw-options-wrapper wrap">
    <h1 class="screen-reader-text"><?php __( 'Product Quotation For WooCommerce', 'pqfw' ); ?></h1>

    <div class="pqfw-options-box postbox">
        <h2 class="pqfw-options-box-header"><?php _e( 'Product Quotation Settings', '' ); ?></h2>
    </div>
</div>

<div class="pqfw-container pqfw-options-wrapper wrap">

    <form action="options.php" id="pqfw-settings-form">

        <div class="pqfw-options-box postbox">
            <h2 class="pqfw-options-box-header"><?php _e( 'General Settings', '' ); ?></h2>
            <div class="pqfw-options-settings-section">
                <ul class="pqfw-flex">


                    <!-- Default Form Style Option Start-->
                    <li><?php _e( 'Use Default Form Style', 'pqfw' ); ?></li>
                    <li>
                        <div class="control switch-control is-rounded">
                            <label for="pqfw_form_default_design">

                                <input
                                        type="checkbox"
                                        class="pqfw-switch-control"
                                        name="pqfw_form_default_design"
                                        id="pqfw_form_default_design"
									<?php checked( $settings['pqfw_form_default_design'], 1 ); ?>
                                >
                                <span class="switch"></span>
                            </label>
                        </div>
                    </li><!-- -->

                    <!-- Floating form style option style-->
                    <li><?php _e( 'Floating Form', 'pqfw' ); ?></li>
                    <li>
                        <div class="control switch-control is-rounded">
                            <label for="pqfw_floating_form">
                                <input
                                        type="checkbox"
                                        class="pqfw-switch-control"
                                        name="pqfw_floating_form"
                                        id="pqfw_floating_form"
									<?php checked( $settings['pqfw_floating_form'], 1 ); ?>
                                >
                                <span class="switch"></span>
                            </label>
                        </div>
                    </li>  <!-- -->
                </ul>
            </div>
        </div>

        <div class="pqfw-options-box postbox">
            <h2 class="pqfw-options-box-header"><?php _e( 'Mail Settings', '' ); ?></h2>
            <div class="pqfw-options-settings-section">
                <ul class="pqfw-flex">

                    <li><?php _e( 'Send Mail For Each Entry', 'pqfw' ); ?></li>
                    <li>
                        <div class="control switch-control is-rounded">
                            <label for="pqfw_form_send_mail">
                                <input
                                        type="checkbox"
                                        class="pqfw-switch-control"
                                        name="pqfw_form_send_mail"
                                        id="pqfw_form_send_mail"
									<?php checked( $settings['pqfw_form_send_mail'], 1 ); ?>
                                >
                                <span class="switch"></span>
                            </label>
                        </div>
                    </li><!-- -->

                </ul>
            </div>
        </div>

        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'pqfw_settings_form_action' ); ?>">
    </form>

</div>