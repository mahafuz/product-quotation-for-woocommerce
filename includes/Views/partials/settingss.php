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
                
            </div>
        </div>

        

        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'pqfw_settings_form_action' ); ?>">
    </form>

</div>