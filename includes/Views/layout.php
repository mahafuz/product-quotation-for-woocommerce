<?php
/**
 * Responsible as the main file of displaying all entries and
 * this is the field of performing all operations.
 *
 * @since   1.0.0
 */

use \PQFW\Database\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Perform soft and hard delete operations.
 * condition is if the action is set and action is delete.
 *
 * @since 1.0.0
 */
if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'delete' ) {

	if ( ! isset( $_REQUEST['_wpnonce'] ) && ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'pqfw_admin_nonce_action' ) ) {
		die( __( 'Unauthorized Operation', 'pqfw' ) );
	}

	/**
	 * Perform bulk action: soft delete.
	 * Get entry ID's array and run the soft delete operation inside a loop till the last ID's in the array.
	 *
	 * @since 1.0.0
	 */
	if ( ! isset( $_REQUEST['pqfw-entries'] ) && isset( $_REQUEST['post'] ) && ! empty( $_REQUEST['post'] ) ) {
		foreach ( $_REQUEST['post'] as $post ) {
			Utils::soft_delete( (int) $post );
		}
	}

	/**
	 * Perform single action: soft delete.
	 * Get entry ID and pass it to the soft_delete.
	 *
	 * after performing operation redirect to entries page.
	 *
	 * @since 1.0.0
	 */
	if ( isset( $_REQUEST['pqfw-entry'] ) && ! empty( $_REQUEST['pqfw-entry'] ) ) {
		Utils::soft_delete( (int) $_REQUEST['pqfw-entry'] );
		wp_safe_redirect( \PQFW\Bootstrap::get_url_with_nonce() );
	}

	/**
	 * Perform bulk action: hard delete.
	 * Get entry ID's array and run the hard delete operation inside a loop till the last ID's in the array.
	 *
	 * @since 1.0.0
	 */
	if ( isset( $_REQUEST['pqfw-entries'] ) && $_REQUEST['pqfw-entries'] === 'trash' && isset( $_REQUEST['post'] ) && ! empty( $_REQUEST['post'] ) ) {
		foreach ( $_REQUEST['post'] as $post ) {
			Utils::delete( (int) $post );
		}

		wp_safe_redirect( \PQFW\Bootstrap::get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entries=trash' ) );
	}

}

/**
 * Perform trash context operations.
 * condition is if it is the Trash context.
 *
 * @since 1.0.0
 */
if ( isset( $_REQUEST['pqfw-entries'] ) && $_REQUEST['pqfw-entries'] === 'trash' ) {

	if ( ! isset( $_REQUEST['_wpnonce'] ) && ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'pqfw_admin_nonce_action' ) ) {
		die( __( 'Unauthorized Operation', 'pqfw' ) );
	}

	/**
	 * Perform bulk action: restore entry.
	 * Get entry ID's array and run the restore operation inside a loop till the last ID's in the array.
	 * after performing operation redirect to entries page.
	 *
	 * @since 1.0.0
	 */
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'restore' && isset( $_REQUEST['post'] ) && ! empty( $_REQUEST['post'] ) ) {
		foreach ( $_REQUEST['post'] as $post ) {
			Utils::restore( (int) $post );
		}

		wp_safe_redirect( \PQFW\Bootstrap::get_url_with_nonce() );
	}

	/**
	 * Perform single action: hard delete.
	 * Get entry ID and pass it to the delete.
	 *
	 * @since 1.0.0
	 */
	if ( isset( $_REQUEST['pqfw-delete-entry'] ) && ! empty( $_REQUEST['pqfw-delete-entry'] ) ) {
		Utils::delete( (int) $_REQUEST['pqfw-delete-entry'] );
		wp_safe_redirect( \PQFW\Bootstrap::get_url_with_nonce( '?page=pqfw-entries-page&pqfw-entries=trash' ) );
	}

	/**
	 * Perform single action: restore.
	 * Get entry ID and pass it to the restore.
	 *
	 * after performing operation redirect to entries page.
	 *
	 * @since 1.0.0
	 */
	if ( isset( $_REQUEST['pqfw-restore-entry'] ) && ! empty( $_REQUEST['pqfw-restore-entry'] ) ) {
		Utils::restore( (int) $_REQUEST['pqfw-restore-entry'] );
		wp_safe_redirect( \PQFW\Bootstrap::get_url_with_nonce() );
	}

}

if ( isset( $_REQUEST['pqfw-entry'] ) && ! empty( $_REQUEST['pqfw-entry'] ) ) {
	include PQFW_PLUGIN_VIEWS . 'partials/entry-details.php';
} else {
	include PQFW_PLUGIN_VIEWS . 'partials/entries.php';
}

