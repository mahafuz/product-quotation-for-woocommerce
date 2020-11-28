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

?>

<div class="pqfw-entries-wrapper wrap">
    <h1 class="screen-reader-text"><?php __( 'Product Quotation For WooCommerce', 'pqfw' ); ?></h1>

  <div class="pqfw-form-entries">
      <?php
        $entries = Utils::fetch_entries(7, 0);

        if( !$entries ) {
            ?>
                <h3><?php _e( 'No Entries Found.', 'pqfw' ); ?></h3>
            <?php

            return;
        }
      ?>

      <div>
          <div>
            <ul class="subsubsub">
                <li class="all"><a href="#" class="current">All <span class="count">(<?php echo esc_attr( Utils::count_entries() ); ?>)</span></a> | </li>
                <li class="trash"><a href="#" class="">Trash<span class="count">(<?php echo esc_attr( Utils::count_entries( 'trash' ) ); ?>)</span></a></li>
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
                      <span class="total-pages"><?php echo esc_attr( Utils::count_entries() ); ?></span>
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
              foreach ( $entries as $entry ) {
                  ?>
                  <tr>
                      <th scope="row" class="check-column"><input type="checkbox" name="post[]" value="<?php echo esc_attr($entry->ID); ?>"></th>
                      <th class="col-entry-id"><a href="?pqfw-entry=?page=pqfw-options-page&pqfw-entry=<?php echo esc_attr($entry->ID); ?>" class="">#<?php echo esc_attr($entry->ID); ?></a></th>
                      <td><span><?php echo esc_attr($entry->fullname); ?></span></td>
                      <td><span><?php echo esc_attr($entry->email); ?></span></td>
                      <th class="col-entry-details">
                          <a href="?page=pqfw-options-page&pqfw-entry=<?php echo esc_attr($entry->ID); ?>" class=""><?php _e( 'Details', 'pqfw' ); ?></a>
                      </th>
                  </tr>
                  <?php
              }
              ?>
              </tbody>

          </table>
      </div>

  </div>
</div>