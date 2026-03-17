<?php
/**
 * Quotify class
 *
 * @author      Mahafuz
 * @package     Quotify
 * @since       1.2.0
 */

namespace Quotify;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for managing plugin ajax requests.
 *
 * @since 2.4.0
 */
class Ajax {

	/**
	 * Contains ajax requests for products.
	 *
	 * @var mixed
	 */
	private $product;

	/**
	 * Contains ajax requests for cart.
	 *
	 * @var mixed
	 */
	private $cart;

	/**
	 * Contains ajax requests for quotations.
	 *
	 * @var mixed
	 */
	private $quotations;

	/**
	 * Contains ajax requests for settings.
	 *
	 * @var mixed
	 */
	private $settings;

	/**
	 * Contains ajax requests for the quotation form.
	 *
	 * @var mixed
	 */
	private $form;

	/**
	 * Contains ajax requests for the addons.
	 *
	 * @var Quotify\Ajax\Addons
	 */
	private $addons;

	/**
	 * Class instance.
	 *
	 * @var Quotify\Ajax
	 */
	private static $instance;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Quotify\Ajax
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize ajax actions.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->quotations = new \Quotify\Ajax\Quotations();
		$this->settings   = new \Quotify\Ajax\Settings();
		$this->addons     = new \Quotify\Ajax\Addons();
		$this->cart       = new \Quotify\Ajax\Cart();
		$this->form       = new \Quotify\Ajax\Form();
	}
}
