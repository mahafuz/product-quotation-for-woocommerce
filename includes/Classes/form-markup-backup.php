<ul class="pqfw-frontend-form">
						<?php pqfw()->controlsManager->generate_fields(); ?>
						<?php if ( pqfw()->settings->get( 'privacy_policy' ) ) : ?>
						<li class="pqfw-privacy-policy">
							<div class="pqfw-privacy-policy-inner">
								<p><?php echo wp_kses_post( pqfw()->helpers->generate_privacy_policy( pqfw()->settings->get( 'privacy_policy_content' ) ) ); ?></p>

								<div class="pqfw-privacy-policy-checkbox">
									<input type="checkbox" name="pqfw_privacy_policy_checkbox" id="pqfw_privacy_policy_checkbox" required="1">
									<label for="pqfw_privacy_policy_checkbox"><?php echo wp_kses_post( pqfw()->helpers->generate_privacy_policy( pqfw()->settings->get( 'privacy_policy_label' ) ) ); ?></label>
								</div>
							</div>
						</li>
						<?php endif; ?>
					</ul>

					<div class="pqfw-form-field pqfw-submit">
						<input
							type="submit"
							id="rsrfqfwc_submit"
							name="rsrfqfwc_submit"
							value="<?php echo esc_html__( 'Submit Query', 'pqfw' ); ?>"
							class="submit"
						/>
						<div class="loading-spinner"></div>
						<?php wp_nonce_field( 'pqfw_form_nonce_action', 'pqfw_form_nonce_field' ); ?>
					</div>

					<div class="pqfw-form-response-status"></div>