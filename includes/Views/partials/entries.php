<?php
/**
 * Responsible for displaying entries.
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use \PQFW\Database\Utils;
use \PQFW\Classes\Entries_Table;
use \PQFW\Bootstrap;

$entries_list_table = new Entries_Table();

$entries = Utils::fetch_entries(
	$entries_list_table->get_per_page(),
	$entries_list_table->get_offset(),
	Utils::get_status( $_REQUEST )
);

?>

<div class="pqfw-entries-wrapper wrap">
    <h1 class="screen-reader-text"><?php __( 'Product Quotation For WooCommerce', 'pqfw' ); ?></h1>

    <form method="POST">
        <div class="pqfw-form-entries">

			<?php $entries_list_table->display( 'top' ); ?>

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
								<?php if ( Utils::get_status( $_REQUEST ) === 'trash' ) : ?>
                                    <span>#<?php echo esc_attr( $entry->ID ); ?></span>
								<?php else : ?>
                                    <a href="<?php echo Bootstrap::get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entry=' . esc_attr( $entry->ID ) ); ?>"
                                       class="">#<?php echo esc_attr( $entry->ID ); ?></a>
								<?php endif; ?>
                            </th>
                            <td><span><?php echo esc_attr( $entry->fullname ); ?></span></td>
                            <td><span><?php echo esc_attr( $entry->email ); ?></span></td>
                            <th class="col-entry-details">
								<?php if ( Utils::get_status( $_REQUEST ) === 'trash' ) : ?>
                                    <a href="<?php echo Bootstrap::get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entries=trash&pqfw-restore-entry=' . esc_attr( $entry->ID ) ); ?>"><?php _e( 'Restore', 'pqfw' ); ?></a>
                                    <span style="color: rgb(221, 221, 221);">|</span> <a
                                            href="<?php echo Bootstrap::get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entries=trash&pqfw-delete-entry=' . esc_attr( $entry->ID ) ); ?>"><?php _e( 'Delete Permanently', 'pqfw' ); ?></a>
								<?php else : ?>
                                    <a href="<?php echo Bootstrap::get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entry=' . esc_attr( $entry->ID ) ); ?>"
                                       class=""><?php _e( 'Details', 'pqfw' ); ?></a>
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
                            <span><?php _e( 'No entries found.', 'pqfw' ); ?></span>
                        </td>
                    </tr>
					<?php
				}
				?>
                </tbody>

            </table>

			<?php //$entries_list_table->display( 'bottom' ); ?>

        </div>
    </form>
</div>