<?php
/**
 * Autoloader class for the entire plugin.
 *
 * @since 2.0.4
 * @package Quotify
 */

namespace PQFW;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Autoloader class for the entire plugin.
 *
 * @since 2.0.4
 */
class Autoload {

	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class Instance.
	 * @since 1.1.0
	 */
	private static $instance;

	/**
	 * Autoload directories for different namespaces.
	 *
	 * @var array
	 */
	private $autoload_directories = [
		'PQFW' => PQFW_PLUGIN_ROOT_DIR_PATH . 'includes/',
	];

	/**
	 * Initiator
	 *
	 * @since 1.1.0
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register autoload directories for namespaces.
	 *
	 * @param string $ir_namespace Namespace to autoload.
	 * @param string $directory Directory path for the namespace.
	 */
	public function add_namespace_directory( $ir_namespace, $directory ) {
		$this->autoload_directories[ $ir_namespace ] = $directory;
	}

	/**
	 * Autoload classes.
	 *
	 * @param string $ir_class Class name.
	 */
	public function autoload( $ir_class ) {
		foreach ( $this->autoload_directories as $ir_namespace => $directory ) {
			if ( 0 === strpos( $ir_class, $ir_namespace ) ) {
				$ir_class_to_load = $ir_class;
				$filename = strtolower(
					preg_replace(
						[ '/^' . $ir_namespace . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
						[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
						$ir_class_to_load
					)
				);
				$file = $directory . $filename . '.php';
				// If the file is readable, include it.
				if ( is_readable( $file ) ) {
					require_once $file;
				}
			}
		}
	}

	/**
	 * Constructor
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );
	}
}


Autoload::get_instance();
