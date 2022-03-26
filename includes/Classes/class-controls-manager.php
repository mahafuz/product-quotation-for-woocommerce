<?php
/**
 * Contains the methods releasted to managing controls.
 *
 * @since   1.0.0
 * @package PQFW
 */

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Control Manager of form fields
 *
 * @package PQFW
 * @since   1.0.0
 */
class Controls_Manager {

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
	 * @var    boolean
	 * @access private
	 * @since  1.0.0
	 */
	private $requiredHTML;

	/**
	 * Required form fields attribute.
	 *
	 * @var    string
	 * @access private
	 * @since  1.0.0
	 */
	private $requiredAttr;

	/**
	 * Constructor of the class
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function __construct() {

		$this->requiredHTML = '<span class="field-required">*</span>';
		$this->requiredAttr = 'required="1"';

		$this->default_fields = [
			[
				'name'     => 'pqfw_customer_name',
				'type'     => 'text',
				'label'    => __( 'Full Name:', 'pqfw' ),
				'html_id'  => 'pqfw_customer_name',
				'required' => true
			],
			[
				'name'     => 'pqfw_customer_email',
				'type'     => 'email',
				'label'    => __( 'Email:', 'pqfw' ),
				'html_id'  => 'pqfw_customer_email',
				'required' => true
			],
			[
				'name'    => 'pqfw_customer_phone',
				'type'    => 'text',
				'label'   => __( 'Phone:', 'pqfw' ),
				'html_id' => 'pqfw_customer_phone',
				// 'required'  => true
			],
			[
				'name'    => 'pqfw_customer_comments',
				'type'    => 'textarea',
				'label'   => __( 'Comments:', 'pqfw' ),
				'html_id' => 'pqfw_customer_comments',
				// 'required'  => true
			],
		];

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
				$type = sanitize_text_field( $field['type'] );
				echo $this->{$type}( $field );
			}
		}

	}

	/**
	 * Generate form field type text.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args The field arguments.
	 * @return string $html
	 */
	protected function text( $args ) {

		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
			'required'    => ''
		];

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', esc_attr( $args['html_class'] ) );

		if ( $args['required'] ) {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s %s</label><input type="text" name="%s" value="%s" %s />',
				esc_attr( $args['name'] ),
				esc_attr( $args['html_id'] ),
				esc_attr( $args['label'] ),
				$this->requiredHTML,
				esc_attr( $args['name'] ),
				esc_attr( $args['value'] ),
				esc_attr( $this->requiredAttr )
			);
		} else {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s</label><input type="text" name="%s" value="%s" />',
				esc_attr( $args['name'] ),
				esc_attr( $args['html_id'] ),
				esc_attr( $args['label'] ),
				esc_attr( $args['name'] ),
				esc_attr( $args['value'] )
			);
		}

		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<p>%s</p>', esc_attr( $args['description'] ) );
		}

		$html .= '</li>';

		return $html;

	}

	/**
	 * Generate form field type email.
	 *
	 * @since   1.0.0
	 *
	 * @param  array $args The field arguments.
	 * @return string $html
	 */
	public function email( $args ) {

		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
			'required'    => ''
		];

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', esc_attr( $args['html_class'] ) );

		if ( $args['required'] ) {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s %s</label><input type="email" name="%s" value="%s" %s />',
				esc_attr( $args['name'] ),
				esc_attr( $args['html_id'] ),
				esc_attr( $args['label'] ),
				$this->requiredHTML,
				esc_attr( $args['name'] ),
				esc_attr( $args['value'] ),
				esc_attr( $this->requiredAttr )
			);
		} else {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s</label><input type="email" name="%s" value="%s" />',
				esc_attr( $args['name'] ),
				esc_attr( $args['html_id'] ),
				esc_attr( $args['label'] ),
				esc_attr( $args['name'] ),
				esc_attr( $args['value'] )
			);
		}

		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<p>%s</p>', esc_attr( $args['description'] ) );
		}

		$html .= '</li>';

		return $html;
	}

	/**
	 * Generate form field type number.
	 *
	 * @since   1.0.0
	 *
	 * @param  array $args  The field arguments.
	 * @return string $html
	 */
	public function number( $args ) {

		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
			'required'    => ''
		];

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', esc_attr( $args['html_class'] ) );

		if ( $args['required'] ) {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s %s</label><input type="number" min="0" name="%s" value="%s" %s />',
				esc_attr( $args['name'] ),
				esc_attr( $args['html_id'] ),
				esc_attr( $args['label'] ),
				$this->requiredHTML,
				esc_attr( $args['name'] ),
				esc_attr( $args['value'] ),
				esc_attr( $this->requiredAttr )
			);
		} else {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s</label><input type="number" min="0" name="%s" value="%s"/>',
				esc_attr( $args['name'] ),
				esc_attr( $args['html_id'] ),
				esc_attr( $args['label'] ),
				esc_attr( $args['name'] ),
				esc_attr( $args['value'] )
			);
		}

		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<p>%s</p>', esc_attr( $args['description'] ) );
		}

		$html .= '</li>';

		return $html;

	}

	/**
	 * Generate form field type textarea.
	 *
	 * @since   1.0.0
	 *
	 * @param  array $args The field arguments.
	 * @return string $html
	 */
	public function textarea( $args ) {

		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
			'required'    => ''
		];

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', esc_attr( $args['html_class'] ) );

		if ( $args['required'] ) {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s %s</label><textarea name="%s" rows="4" %s>%s</textarea>',
				esc_attr( $args['name'] ),
				esc_attr( $args['html_id'] ),
				esc_attr( $args['label'] ),
				$this->requiredHTML,
				esc_attr( $args['name'] ),
				esc_attr( $this->requiredAttr ),
				esc_attr( $args['value'] )
			);
		} else {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s</label><textarea name="%s" rows="4">%s</textarea>',
				esc_attr( $args['name'] ),
				esc_attr( $args['html_id'] ),
				esc_attr( $args['label'] ),
				esc_attr( $args['name'] ),
				esc_attr( $args['value'] )
			);
		}

		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<p>%s</p>', esc_attr( $args['description'] ) );
		}

		$html .= '</li>';

		return $html;
	}
}