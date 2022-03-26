<div class="pqfw-settings-section">
    <div class="pqfw-options-section-header">
        <h3 class="pqfw-options-section-title"><?php _e( 'Mail Settings', '' ); ?></h3>
    </div>
    <div class="pqfw-settings-options">
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
                            <?php //checked( $settings['pqfw_form_default_design'], 1 ); ?>
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
                            <?php //checked( $settings['pqfw_floating_form'], 1 ); ?>
                        >
                        <span class="switch"></span>
                    </label>
                </div>
            </li>  <!-- -->
        </ul>
    </div>
</div>