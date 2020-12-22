<?php

/**
 * Responsible for displaying settings page.
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
?>

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