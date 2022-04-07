<?php
/**
 * Responsible for displaying single entry.
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


$entry = pqfw()->utils->fetch_entry( absint( $_REQUEST['pqfw-entry'] ) );

?>

<div class="wrap">
    <div>
        <div class="child">
            <div class="pqfw-entry grand-child">
                <h1 class="screen-reader-text"><?php __( 'Product Quotation For WooCommerce', 'pqfw' ); ?></h1>
				<?php
				if ( ! $entry ) {
					?>
                    <h4><?php _e( 'No Entry Found.', 'pqfw' ); ?></h4>
					<?php

					return;
				}
				?>

                <h1 class="wp-heading-inline"><?php _e( 'Entry Details', 'pqfw' ); ?></h1>
                <a href="<?php echo esc_url( pqfw()->get_url_with_nonce( 'edit.php?post_type=pqfw_quotations&page=pqfw-entries-page' ) ); ?>"
                   class="page-title-action"><?php _e( 'Back to Entries', 'pqfw' ); ?></a>
                <form method="POST">
                    <div class="pqfw-entry-wrap">
                        <div class="pqfw-entry-left">
                            <div class="postbox">
                                <h2 class="hndle ui-sortable-handle">
                                    <span><?php _e( 'Entry', 'pqfw' ); ?> # <?php echo esc_attr( $entry->ID ); ?></span>
                                </h2>
                                <div class="main">
                                    <table class="wp-list-table widefat fixed striped posts">
                                        <tbody>
                                        <tr class="field-label wrong-answer">
                                            <th>
                                                <strong><?php _e( 'Product ID', 'pqfw' ); ?></strong>
                                            </th>
                                        </tr>
                                        <tr class="field-value wrong-answer">
                                            <td>
                                                <div>
                                                    <p><?php echo esc_attr( $entry->product_id ); ?></p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="field-label wrong-answer">
                                            <th>
                                                <strong><?php _e( 'Product Quantity', 'pqfw' ); ?></strong>
                                            </th>
                                        </tr>
                                        <tr class="field-value wrong-answer">
                                            <td>
                                                <div>
                                                    <p><?php echo esc_attr( $entry->quantity ); ?></p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="field-label wrong-answer">
                                            <th>
                                                <strong><?php _e( 'Product Page', 'pqfw' ); ?></strong>
                                            </th>
                                        </tr>
                                        <tr class="field-value wrong-answer">
                                            <td>
                                                <div>
                                                    <a href="<?php echo esc_url( get_permalink( $entry->product_id ) ); ?>"
                                                       target="_blank"><?php echo esc_attr( $entry->product_title ); ?></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="field-label wrong-answer">
                                            <th>
                                                <strong><?php _e( 'Comments', 'pqfw' ); ?></strong>
                                            </th>
                                        </tr>
                                        <tr class="field-value wrong-answer">
                                            <td>
                                                <div>
                                                    <p><?php echo esc_attr( $entry->comments ); ?></p>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="pqfw-entry-right">
                            <div class="postbox">
                                <h2 class="hndle ui-sortable-handle">
                                    <span><?php _e( 'Submission Info', 'pqfw' ); ?></span></h2>
                                <div class="inside">
                                    <div class="main">
                                        <ul>
                                            <li>
                                                <span class="label"><?php _e( 'Name', 'pqfw' ); ?></span>
                                                <span class="sep"> : </span>
                                                <span class="value"><?php echo esc_attr( $entry->fullname ); ?></span>
                                            </li>
                                            <li>
                                                <span class="label"><?php _e( 'Email', 'pqfw' ); ?></span>
                                                <span class="sep"> : </span>
                                                <span class="value"><?php echo esc_attr( $entry->email ); ?></span>
                                            </li>
                                            <li><span class="label"><?php _e( 'Phone', 'pqfw' ); ?></span>
                                                <span class="sep"> : </span>
                                                <span class="value"><?php echo esc_attr( $entry->phone ); ?></span>
                                            </li>
                                            <li>
                                                <span class="label"><?php _e( 'Submitted On', 'pqfw' ); ?></span>
                                                <span class="sep"> : </span>
                                                <span class="value"><?php echo esc_attr( $entry->dateadded ); ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="major-publishing-actions">
                                    <div id="publishing-action">
                                        <input type="hidden" name="action" value="delete"/>
                                        <button class="button button-large button-secondary">
                                            <span class="dashicons dashicons-trash"></span> <?php _e( 'Delete', 'pqfw' ); ?>
                                        </button>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="clear"></div>