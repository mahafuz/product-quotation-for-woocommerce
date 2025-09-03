<?php
/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace PQFW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	public $product;

	/**
	 * Contains ajax requests for cart.
	 *
	 * @var mixed
	 */
	public $cart;

	/**
	 * Contains ajax requests for quotations.
	 *
	 * @var mixed
	 */
	public $quotations;

	/**
	 * Initialize ajax actions.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->product = new \PQFW\Ajax\Product();
		$this->cart = new \PQFW\Ajax\Cart();
		$this->quotations = new \PQFW\Ajax\Quotations();
	}
}
