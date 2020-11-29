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

$entries = Utils::fetch_entries(7, 0, Utils::get_status( $_REQUEST ) );

?>

<div class="pqfw-entries-wrapper wrap">
    <h1 class="screen-reader-text"><?php __( 'Product Quotation For WooCommerce', 'pqfw' ); ?></h1>

    <form method="POST">
        <div class="pqfw-form-entries">
            <div>
                <div>
                    <ul class="subsubsub">
                        <li class="all"><a href="?page=pqfw-options-page"<?php echo Utils::get_status( $_REQUEST ) === 'publish' ? ' class="current"' : ''; ?>><?php _e( 'All', 'pqfw' ); ?> <span class="count">(<?php echo esc_attr( Utils::count_entries() ); ?>)</span></a> | </li>
                        <li class="trash"><a href="?page=pqfw-options-page&pqfw-entries=trash"<?php echo Utils::get_status( $_REQUEST ) === 'trash' ? ' class="current"' : ''; ?>><?php _e( 'Trash', 'pqfw' ); ?><span class="count">(<?php echo esc_attr( Utils::count_entries( 'trash' ) ); ?>)</span></a></li>
                    </ul>
                </div>
            </div>

            <div>

                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-top" class="screen-reader-text"><?php _e( 'Select bulk action', 'pqfw' ); ?></label>
                        <select name="action">
                            <option value="-1"><?php _e( 'Bulk Actions', 'pqfw' ); ?></option>
                            <option value="delete"><?php _e( 'Delete Entries', 'pqfw' ); ?></option>
                            <?php if( Utils::get_status( $_REQUEST ) === 'trash' ) : ?>
                            <option value="restore"><?php _e( 'Restore', 'pqfw' ); ?></option>
                            <?php endif; ?>
                        </select>
                        <button class="button action"><?php _e( 'Apply', 'pqfw' ); ?></button>
                    </div>
                    <!--              <div class="alignleft actions">-->
                    <!--                    <a href="admin-post.php?action=weforms_export_form_entries&amp;selected_forms=59&amp;_wpnonce=7e3e8d9e48" class="button" style="margin-top: 0px;">-->
                    <!--                      <span class="dashicons dashicons-download" style="margin-top: 4px;"></span>-->
                    <!--                      Export Entries-->
                    <!--                    </a>-->
                    <!--              </div>-->
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php echo esc_attr( count($entries) ); ?> <?php _e( 'items', 'pqfw' ); ?></span>
                        <span class="pagination-links"><span aria-hidden="true" class="tablenav-pages-navspan">«</span>
                        <span aria-hidden="true" class="tablenav-pages-navspan">‹</span>
                        <span class="screen-reader-text"><?php _e( 'Current Page', 'pqfw' ); ?></span>

                        <input id="current-page-selector" type="text" value="1" size="1" aria-describedby="table-paging" class="current-page"> <?php _e( 'of', 'pqfw' ); ?>

                        <?php if( Utils::get_status( $_REQUEST ) === 'publish' ) : ?>
                        <span class="total-pages"><?php echo esc_attr( Utils::count_entries() ); ?></span>
                        <?php endif; ?>

                        <?php if( Utils::get_status( $_REQUEST ) === 'trash' ) : ?>
                        <span class="total-pages"><?php echo esc_attr( Utils::count_entries( 'trash' ) ); ?></span>
                        <?php endif; ?>

                        <span aria-hidden="true" class="tablenav-pages-navspan">›</span>
                        <span aria-hidden="true" class="tablenav-pages-navspan">»</span>
                  </span>
                    </div>
                </div>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <input type="checkbox">
                        </td>
                        <th class="col-entry-id"><?php _e('ID', 'pqfw' ); ?></th>
                        <th><?php _e('Name', 'pqfw' ); ?></th>
                        <th><?php _e('Email Address', 'pqfw' ); ?></th>
                        <th><?php _e('Actions', 'pqfw' ); ?></th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <input type="checkbox">
                        </td>
                        <th class="col-entry-id"><?php _e('ID', 'pqfw' ); ?></th>
                        <th><?php _e('Name', 'pqfw' ); ?></th>
                        <th><?php _e('Email Address', 'pqfw' ); ?></th>
                        <th><?php _e('Actions', 'pqfw' ); ?></th>
                    </tr>
                    </tfoot>

                    <tbody>
                    <?php
                        if( $entries ) {
                            foreach ( $entries as $entry ) {
                                ?>
                                <tr>
                                    <th scope="row" class="check-column"><input type="checkbox" name="post[]" value="<?php echo esc_attr($entry->ID); ?>"></th>
                                    <th class="col-entry-id">
                                        <?php if( Utils::get_status( $_REQUEST ) === 'trash' ) : ?>
                                            <span>#<?php echo esc_attr($entry->ID); ?></span>
                                        <?php else : ?>
                                            <a href="?page=pqfw-options-page&pqfw-entry=<?php echo esc_attr( $entry->ID ); ?>" class="">#<?php echo esc_attr($entry->ID); ?></a>
                                        <?php endif; ?>
                                    </th>
                                    <td><span><?php echo esc_attr($entry->fullname); ?></span></td>
                                    <td><span><?php echo esc_attr($entry->email); ?></span></td>
                                    <th class="col-entry-details">
                                        <?php if( Utils::get_status( $_REQUEST ) === 'trash' ) : ?>
                                            <a href="?page=pqfw-options-page&pqfw-entries=trash&pqfw-restore-entry=<?php echo esc_attr($entry->ID); ?>"><?php _e( 'Restore', 'pqfw' ); ?></a>
                                            <span style="color: rgb(221, 221, 221);">|</span> <a href="?page=pqfw-options-page&pqfw-entries=trash&pqfw-delete-entry=<?php echo esc_attr($entry->ID); ?>"><?php _e( 'Delete Permanently', 'pqfw' ); ?></a>
                                        <?php else : ?>
                                            <a href="?page=pqfw-options-page&pqfw-entry=<?php echo esc_attr( $entry->ID ); ?>" class=""><?php _e( 'Details', 'pqfw' ); ?></a>
                                        <?php endif; ?>
                                    </th>
                                </tr>
                                <?php
                            }
                        }else {
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
            </div>

        </div>
    </form>
</div>