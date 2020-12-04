<?php

namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Control Manager of form fields
 *
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */
class Controls_Manager {

	/**
	 * Single instance of the class
	 *
	 * @var \Controls_Manager
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Returns single instance of the class
	 *
	 * @return \Controls_Manager
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			return self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Form fields container.
	 *
	 * @var     array
	 * @access  protected
	 * @since   1.0.0
	 */
	protected $fields;

	/**
	 * Form default fields container.
	 *
	 * @var     array
	 * @access  protected
	 * @since   1.0.0
	 */
	private $default_fields;

	/**
	 * Required form fields html.
	 *
	 * @access  private
	 * @since   1.0.0
	 */
	private $requiredHTML;

	/**
	 * Required form fields attribute.
	 *
	 * @access  private
	 * @since   1.0.0
	 */
	private $requiredAttr;

	/**
	 * Constructor of the class
	 *
	 * @return \Controls_Manager
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->requiredHTML = '<span class="field-required">*</span>';
		$this->requiredAttr = 'required="1"';

		/**
		 * default form fields.
		 *
		 * @since 1.0.0
		 */
		$this->default_fields = array (
			array (
				'name'     => 'pqfw_product_quantity',
				'type'     => 'number',
				'label'    => __( 'Quantity:', 'pqfw' ),
				'html_id'  => 'pqfw_product_quantity',
				'required' => true
			),
			array (
				'name'     => 'pqfw_customer_name',
				'type'     => 'text',
				'label'    => __( 'Full Name:', 'pqfw' ),
				'html_id'  => 'pqfw_customer_name',
				'required' => true
			),
			array (
				'name'     => 'pqfw_customer_email',
				'type'     => 'email',
				'label'    => __( 'Email:', 'pqfw' ),
				'html_id'  => 'pqfw_customer_email',
				'required' => true
			),
			array (
				'name'    => 'pqfw_customer_phone',
				'type'    => 'text',
				'label'   => __( 'Phone:', 'pqfw' ),
				'html_id' => 'pqfw_customer_phone',
//                'required'  => true
			),
			array (
				'name'    => 'pqfw_customer_comments',
				'type'    => 'textarea',
				'label'   => __( 'Comments:', 'pqfw' ),
				'html_id' => 'pqfw_customer_comments',
//                'required'  => true
			),
		);

		$this->fields = apply_filters( 'pqfw_add_form_fields', $this->default_fields );

	}

	/**
	 * Generate form fields based on field type.
	 *
	 * @var     array $this ->fields
	 * @access  public
	 */
	public function generate_fields() {

		if ( $this->fields ) {
			foreach ( $this->fields as $field ) {
				// TODO: Type checking.
				$type = $field['type'];
				echo $this->{$type}( $field );
			}
		}

	}

	/**
	 * Generate form field type text.
	 *
	 * @access  protected
	 *
	 * @param array $args
	 *
	 * @return  string      $html
	 * @since   1.0.0
	 */
	protected function text( $args ) {

		$defaults = array (
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
			'required'    => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', $args['html_class'] );

		if ( $args['required'] ) {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s %s</label><input type="text" name="%s" value="%s" %s />',
				$args['name'], $args['html_id'], $args['label'], $this->requiredHTML, $args['name'], $args['value'], $this->requiredAttr
			);
		} else {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s</label><input type="text" name="%s" value="%s" />',
				$args['name'], $args['html_id'], $args['label'], $args['name'], $args['value']
			);
		}


		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<p>%s</p>', $args['description'] );
		}

		$html .= '</li>';

		return $html;

	}

	/**
	 * Generate form field type email.
	 *
	 * @access  protected
	 *
	 * @param array $args
	 *
	 * @return  string      $html
	 * @since   1.0.0
	 */
	public function email( $args ) {

		$defaults = array (
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
			'required'    => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', $args['html_class'] );

		if ( $args['required'] ) {
			$html .= sprintf( '<label for="%s" class="pqfw-form-label" id="%s">%s %s</label><input type="email" name="%s" value="%s" %s />', $args['name'], $args['html_id'], $args['label'], $this->requiredHTML, $args['name'], $args['value'], $this->requiredAttr );
		} else {
			$html .= sprintf( '<label for="%s" class="pqfw-form-label" id="%s">%s</label><input type="email" name="%s" value="%s" />', $args['name'], $args['html_id'], $args['label'], $args['name'], $args['value'] );
		}

		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<p>%s</p>', $args['description'] );
		}

		$html .= '</li>';

		return $html;

	}

	/**
	 * Generate form field type number.
	 *
	 * @access  protected
	 *
	 * @param array $args
	 *
	 * @return  string      $html
	 * @since   1.0.0
	 */
	public function number( $args ) {

		$defaults = array (
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
			'required'    => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', $args['html_class'] );

		if ( $args['required'] ) {
			$html .= sprintf( '<label for="%s" class="pqfw-form-label" id="%s">%s %s</label><input type="number" min="0" name="%s" value="%s" %s />', $args['name'], $args['html_id'], $args['label'], $this->requiredHTML, $args['name'], $args['value'], $this->requiredAttr );
		} else {
			$html .= sprintf( '<label for="%s" class="pqfw-form-label" id="%s">%s</label><input type="number" min="0" name="%s" value="%s"/>', $args['name'], $args['html_id'], $args['label'], $args['name'], $args['value'] );
		}

		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<p>%s</p>', $args['description'] );
		}

		$html .= '</li>';

		return $html;

	}

	/**
	 * Generate form field type textarea.
	 *
	 * @access  protected
	 *
	 * @param array $args
	 *
	 * @return  string      $html
	 * @since   1.0.0
	 */
	public function textarea( $args ) {

		$defaults = array (
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
			'required'    => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', $args['html_class'] );

		if ( $args['required'] ) {
			$html .= sprintf( '<label for="%s" class="pqfw-form-label" id="%s">%s %s</label><textarea name="%s" rows="4" %s>%s</textarea>', $args['name'], $args['html_id'], $args['label'], $this->requiredHTML, $args['name'], $this->requiredAttr, $args['value'] );
		} else {
			$html .= sprintf( '<label for="%s" class="pqfw-form-label" id="%s">%s</label><textarea name="%s" rows="4">%s</textarea>', $args['name'], $args['html_id'], $args['label'], $args['name'], $args['value'] );
		}

		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<p>%s</p>', $args['description'] );
		}

		$html .= '</li>';

		return $html;
	}

}