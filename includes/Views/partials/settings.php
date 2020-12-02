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
                                <input type="checkbox" class="pqfw-switch-control" name="pqfw_form_default_design" id="pqfw_form_default_design" >
                                <span class="switch"></span>
                            </label>
                        </div>
                    </li><!-- -->

                    <!-- Floating form style option style-->
                    <li><?php _e( 'Floating Form', 'pqfw' ); ?></li>
                    <li>
                        <div class="control switch-control is-rounded">
                            <label for="pqfw_floating_form">
                                <input type="checkbox" class="pqfw-switch-control" name="pqfw_floating_form" id="pqfw_floating_form" >
                                <span class="switch"></span>
                            </label>
                        </div>
                    </li>  <!-- -->
                </ul>
            </div>
        </div>
        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'pqfw_settings_form_action' ); ?>">
    </form>

    <div class="pqfw-options-box postbox">
        <h2 class="pqfw-options-box-header"><?php _e( 'Style Settings', '' ); ?></h2>
        <div class="pqfw-options-settings-section">
            <ul class="pqfw-flex">

                <div style="background-color: #a3d4a3; display: block; width: 100%; text-align: center; margin: 5px; border-radius: 3px;">
                    <h4><?php _e( 'Coming in Future Version!', 'pqfw' ); ?></h4>
                </div>

            </ul>
        </div>
    </div>
</div>