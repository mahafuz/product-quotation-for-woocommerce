<div class="pqfw-settings-section">
    <div class="pqfw-options-section-header">
        <h3 class="pqfw-options-section-title"><?php _e( 'Mail Settings', '' ); ?></h3>
    </div>
    <div class="pqfw-settings-options">
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
                            <?php //checked( $settings['pqfw_form_send_mail'], 1 ); ?>
                        >
                        <span class="switch"></span>
                    </label>
                </div>
            </li><!-- -->

        </ul>
    </div>
</div>