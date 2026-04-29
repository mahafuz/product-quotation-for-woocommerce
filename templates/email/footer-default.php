<?php
/**
 * Email Footer Template Part
 *
 * @since 2.6.0
 * @package Quotify
 *
 * @param array $args {
 *     Footer arguments.
 *
 *     @type string $site_name Site name for footer.
 *     @type string $site_url  Site URL for footer link.
 * }
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$defaults = [
	'site_name' => get_bloginfo( 'name' ),
	'site_url'  => get_bloginfo( 'url' ),
];

$args = wp_parse_args( $args, $defaults );
?>
					</td>
				</tr>
			</table>

			<div class="footer">
				<table role="presentation" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="content-block">
							<span class="powered-by">
								<?php
								/**
								 * Fires in the email footer.
								 *
								 * @since 2.6.0
								 *
								 * @param array $args Footer arguments.
								 */
								do_action( 'quotify_email_footer_text', $args );
								?>
								<?php esc_html_e( 'This email was sent by', 'quotify' ); ?>
								<a href="<?php echo esc_url( $args['site_url'] ); ?>"><?php echo esc_html( $args['site_name'] ); ?></a>
							</span>
						</td>
					</tr>
				</table>
			</div>

			<?php
			/**
			 * Fires after the email footer content.
			 *
			 * @since 2.6.0
			 *
			 * @param array $args Footer arguments.
			 */
			do_action( 'quotify_email_footer_after', $args );
			?>
		</div>
	</div>
</body>
</html>
