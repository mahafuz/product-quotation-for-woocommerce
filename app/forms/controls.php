<?php
/**
 * Contains the methods related to managing controls.
 *
 * @since   1.0.0
 * @package Quotify
 */

namespace Quotify\Forms;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Control Manager of form fields
 *
 * @package Quotify
 * @since   1.0.0
 */
class Controls {
	/**
	 * Class instance.
	 *
	 * @var \Quotify\Forms\Controls
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return \Quotify\Forms\Controls
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
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
	private function __construct() {
		$this->requiredHTML = '<span class="field-required">*</span>';
		$this->requiredAttr = 'required="1"';

		$customization_enabled = quotify()->settings()->get( 'pqfw_form_fields_customization_enabled' );

		if ( $customization_enabled ) {
			$name_label = quotify()->settings()->get( 'pqfw_field_name_label' );
			$name_required = quotify()->settings()->get( 'pqfw_field_name_required' );
			$name_enabled = quotify()->settings()->get( 'pqfw_field_name_enabled' );

			$email_label = quotify()->settings()->get( 'pqfw_field_email_label' );
			$email_required = quotify()->settings()->get( 'pqfw_field_email_required' );
			$email_enabled = quotify()->settings()->get( 'pqfw_field_email_enabled' );

			$phone_label = quotify()->settings()->get( 'pqfw_field_phone_label' );
			$phone_required = quotify()->settings()->get( 'pqfw_field_phone_required' );
			$phone_enabled = quotify()->settings()->get( 'pqfw_field_phone_enabled' );

			$comments_label = quotify()->settings()->get( 'pqfw_field_comments_label' );
			$comments_required = quotify()->settings()->get( 'pqfw_field_comments_required' );
			$comments_enabled = quotify()->settings()->get( 'pqfw_field_comments_enabled' );
		} else {
			$name_label = __( 'Full Name:', 'quotify' );
			$name_required = true;
			$name_enabled = true;

			$email_label = __( 'Email:', 'quotify' );
			$email_required = true;
			$email_enabled = true;

			$phone_label = __( 'Phone:', 'quotify' );
			$phone_required = false;
			$phone_enabled = true;

			$comments_label = __( 'Comments:', 'quotify' );
			$comments_required = false;
			$comments_enabled = true;
		}

		$this->default_fields = [];

		if ( $name_enabled ) {
			$this->default_fields[] = [
				'name'     => 'pqfw_customer_name',
				'type'     => 'text',
				'label'    => $name_label,
				'html_id'  => 'pqfw_customer_name',
				'required' => $name_required,
			];
		}

		if ( $email_enabled ) {
			$this->default_fields[] = [
				'name'     => 'pqfw_customer_email',
				'type'     => 'email',
				'label'    => $email_label,
				'html_id'  => 'pqfw_customer_email',
				'required' => $email_required,
			];
		}

		$custom_subjects_enabled = quotify()->settings()->get( 'pqfw_custom_email_subject_enabled' );
		$subject_enabled = $customization_enabled ? quotify()->settings()->get( 'pqfw_field_subject_enabled' ) : true;
		$subject_required = $customization_enabled ? quotify()->settings()->get( 'pqfw_field_subject_required' ) : true;
		$subject_label = $customization_enabled ? quotify()->settings()->get( 'pqfw_field_subject_label' ) : __( 'Subject:', 'quotify' );

		if ( ! $custom_subjects_enabled && $subject_enabled ) {
			$this->default_fields[] = [
				'name'     => 'pqfw_customer_subject',
				'type'     => 'text',
				'label'    => $subject_label,
				'html_id'  => 'pqfw_customer_subject',
				'required' => $subject_required,
			];
		}

		if ( $phone_enabled ) {
			$this->default_fields[] = [
				'name'    => 'pqfw_customer_phone',
				'type'    => 'text',
				'label'   => $phone_label,
				'html_id' => 'pqfw_customer_phone',
				'required' => $phone_required,
			];
		}

		if ( $comments_enabled ) {
			$this->default_fields[] = [
				'name'    => 'pqfw_customer_comments',
				'type'    => 'textarea',
				'label'   => $comments_label,
				'html_id' => 'pqfw_customer_comments',
				'required' => $comments_required,
			];
		}

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
				echo \Quotify\Library\Helper::escape_html_form( $this->{$type}( $field ) ); //phpcs:ignore
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
			'required'    => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$html = sprintf( '<li class="pqfw-form-field%s">', esc_attr( $args['html_class'] ) );

		if ( $args['required'] ) {
			$html .= sprintf(
				'<label for="%s" class="pqfw-form-label" id="%s">%s %s</label>
				<input type="text" name="%s" value="%s" %s />',
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
			'required'    => '',
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
			'required'    => '',
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
			'required'    => '',
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
