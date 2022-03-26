<?php
/**
 * Responsible for displaying entries.
 *
 * @since 1.0.0
 * @package PQFW
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$entries = pqfw()->utils->fetch_entries(
	pqfw()->table->get_per_page(),
	pqfw()->table->get_offset(),
	pqfw()->utils->get_status( $_REQUEST )
);

?>

<div class="pqfw-entries-wrapper wrap">
	<h1 class="screen-reader-text"><?php esc_html__( 'Product Quotation For WooCommerce', 'pqfw' ); ?></h1>

	<form method="POST">
		<div class="pqfw-form-entries">

		<?php pqfw()->table->display( 'top' ); ?>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<input type="checkbox">
						</td>
						<th class="col-entry-id"><?php _e( 'ID', 'pqfw' ); ?></th>
						<th><?php _e( 'Name', 'pqfw' ); ?></th>
						<th><?php _e( 'Email Address', 'pqfw' ); ?></th>
						<th><?php _e( 'Actions', 'pqfw' ); ?></th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<input type="checkbox">
						</td>
						<th class="col-entry-id"><?php _e( 'ID', 'pqfw' ); ?></th>
						<th><?php _e( 'Name', 'pqfw' ); ?></th>
						<th><?php _e( 'Email Address', 'pqfw' ); ?></th>
						<th><?php _e( 'Actions', 'pqfw' ); ?></th>
					</tr>
				</tfoot>

				<tbody>
				<?php
				if ( $entries ) {
					foreach ( $entries as $entry ) {
						?>
						<tr>
							<th scope="row" class="check-column"><input type="checkbox" name="post[]" value="<?php echo esc_attr( $entry->ID ); ?>">
							</th>

							<th class="col-entry-id">
								<?php if ( 'trash' === pqfw()->utils->get_status( $_REQUEST ) ) : ?>
								<span>#<?php echo esc_attr( $entry->ID ); ?></span>
								<?php else : ?>
								<a href="<?php echo esc_url( pqfw()->get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entry=' . esc_attr( $entry->ID ) ) ); ?>"
								class="">#<?php echo esc_attr( $entry->ID ); ?></a>
								<?php endif; ?>
							</th>
							<td><span><?php echo esc_attr( $entry->fullname ); ?></span></td>
							<td><span><?php echo esc_attr( $entry->email ); ?></span></td>
							<th class="col-entry-details">
								<?php if ( pqfw()->utils->get_status( $_REQUEST ) === 'trash' ) : ?>
									<a href="<?php echo esc_url( pqfw()->get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entries=trash&pqfw-restore-entry=' . esc_attr( $entry->ID ) ) ); ?>">
										<?php esc_html_e( 'Restore', 'pqfw' ); ?>
									</a>
									<span style="color: rgb(221, 221, 221);">|</span>
									<a href="<?php echo esc_url( pqfw()->get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entries=trash&pqfw-delete-entry=' . esc_attr( $entry->ID ) ) ); ?>">
										<?php esc_html_e( 'Delete Permanently', 'pqfw' ); ?>
									</a>
								<?php else : ?>
									<a href="<?php echo esc_url( pqfw()->get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entry=' . esc_attr( $entry->ID ) ) ); ?>"
									class=""><?php esc_html_e( 'Details', 'pqfw' ); ?></a>
								<?php endif; ?>
							</th>
						</tr>
						<?php
					}
				} else {
					?>
					<tr>
						<th></th>
						<td>
							<span><?php esc_html_e( 'No entries found.', 'pqfw' ); ?></span>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
	</form>
</div>