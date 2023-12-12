<?php
namespace PQFW;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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
	private $autoload_directories = array(
		'PQFW' => PQFW_PLUGIN_PATH . 'includes/',
	);

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
	 * @param string $namespace Namespace to autoload.
	 * @param string $directory Directory path for the namespace.
	 */
	public function add_namespace_directory( $namespace, $directory ) {
		$this->autoload_directories[ $namespace ] = $directory;
	}

	/**
	 * Autoload classes.
	 *
	 * @param string $class Class name.
	 */
	public function autoload( $class ) {
		foreach ( $this->autoload_directories as $namespace => $directory ) {
			if ( 0 === strpos( $class, $namespace ) ) {
				$class_to_load = $class;
				$filename = strtolower(
					preg_replace(
						[ '/^' . $namespace . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
						[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
						$class_to_load
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