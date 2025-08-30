<?php

/**
 * PQFW class
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.2.0
 */

namespace PQFW; 


class Ajax {

	public $product;
	public $cart;
	public $quotations;
	

	/**
	 * Initialize ajax actions.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->product = new \PQFW\Ajax\Product;
		$this->cart = new \PQFW\Ajax\Cart;
		$this->quotations =  new \PQFW\Ajax\Quotations;
	}
}