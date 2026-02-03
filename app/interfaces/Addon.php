<?php
/**
 * Addon interface for PQFW plugin extensions.
 *
 * Defines the standard structure for all addons to ensure consistency
 * and required functionality across all extensions. This interface
 * enforces a modular architecture with clear separation of concerns.
 *
 * @since 1.0.0
 * @package Quotify
 */

namespace PQFW\Utils\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Interface Addon
 *
 * @since 1.0.0
 */
interface Addon {

	/**
	 * Initialize the addon.
	 *
	 * Static bootstrap method for addon initialization. Typically used to:
	 * - Instantiate the addon class
	 * - Register activation/deactivation hooks
	 * - Set up early hooks
	 *
	 * @since 1.0.0
	 * @static
	 * @return void
	 */
	public static function init();

	/**
	 * Define addon-specific constants.
	 *
	 * Should define all necessary constants for the addon including:
	 * - Version constants
	 * - File and directory paths
	 * - URL paths for assets
	 * - Database table names
	 * - Custom post type and taxonomy slugs
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function define_constants();

	/**
	 * Initialize addon core functionality.
	 *
	 * Main method to set up all addon features including:
	 * - Hook registrations (actions & filters)
	 * - Shortcode registrations
	 * - Post type and taxonomy registrations
	 * - Admin menu and page setup
	 * - Asset enqueuing hooks
	 * - Custom widget registrations
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init_addon();

	/**
	 * Handle database operations.
	 *
	 * Responsible for all database-related functionality including:
	 * - Database table creation/updates
	 * - CRUD operations
	 * - Data validation and sanitization
	 * - Database migrations
	 * - Query optimization
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function database();

	/**
	 * Handle AJAX operations.
	 *
	 * Manages all AJAX-related functionality including:
	 * - AJAX hook registrations (both privileged and nopriv)
	 * - AJAX request validation and sanitization
	 * - AJAX response handling
	 * - Nonce verification
	 * - Error handling for AJAX requests
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function ajax();

	/**
	 * Addon activation hook.
	 *
	 * Handles tasks to be performed when the addon is activated:
	 * - Database table creation and schema updates
	 * - Default option and setting initialization
	 * - Initial data population
	 * - User capability and role management
	 * - Scheduled event setup
	 * - Version tracking and migration checks
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function addon_activation_hook();
}
