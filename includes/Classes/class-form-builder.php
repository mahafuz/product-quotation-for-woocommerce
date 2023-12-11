<?php

namespace PQFW\Classes;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Handles testimonial submission form.
 *
 * @since 2.0.7
 */
class Form_Builder {

	public $form_markup;

	/**
	 * Class constructor.
	 *
	 * @since 2.0.5
	 */
	public function __construct() {
		add_shortcode( 'pqfw_quote_form', [ $this, 'render' ] );
	}

	/**
	 * Generate input type field name.
	 *
	 * @since 2.0.7
	 *
	 * @param  string $label Form field label.
	 * @return string         Generated field name.
	 */
	public function getFieldName( $label ) {
		return str_replace( ' ', '_', trim( strtolower( $label ) ) );
	}

	/**
	 * Degenerate input type field name.
	 *
	 * @since 2.0.7
	 *
	 * @param  string $label Form field label.
	 * @return string         Generated field name.
	 */
	public function dGenerateFieldName( $label ) {
		return str_replace( '_', ' ', trim( strtolower( $label ) ) );
	}

	/**
	 * Get form markup.
	 *
	 * @since 2.0.4
	 */
	public function get_form_markup() {
		$default_form = '[{"id":"ECCABDAD-43C6-46BF-A7DD-F5D9F78F3062","element":"TextInput","text":"Full Name","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Full Name"},{"id":"F6C2115E-A76E-4C6C-AC14-892F69CBD197","element":"TextInput","text":"Subject","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Subject"},{"id":"7891386B-6EC1-4490-A8CA-E0D5C4C51CC5","element":"TextInput","text":"Email","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Email"},{"id":"A2417105-B266-4537-A14F-5282951710EF","element":"TextInput","text":"Phone\/Mobile","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Phone\/Mobile"},{"id":"07C2E624-F875-43E4-94CF-B0A0A0E60237","element":"TextArea","text":"Comments","required":false,"canHaveAnswer":true,"canHavePageBreakBefore":true,"canHaveAlternateForm":true,"canHaveDisplayHorizontal":true,"canHaveOptionCorrect":true,"canHaveOptionValue":true,"canPopulateFromApi":true,"label":"Comments"}]';

		return json_decode( $default_form );
	}

	/**
	 * Render form frontend.
	 *
	 * @since 2.0.7
	 */
	public function render() {
		$formData = $this->get_form_markup();
		$html     = '';

		$html .= sprintf( '<h2 class="pqfw-form-title">%s</h2>', pqfw()->settings->get( 'formTitle', __( 'Testimonial Submission Form', 'pqfw' ) ) );

		if ( $formData ) {
			foreach ( $formData as $field ) {
				$fieldType = $field->element;

				if ( 'TextInput' === $fieldType ) {
					$html .= sprintf( '<div class="form-group field_type_text %s">', $this->getFieldName( $field->label ) );
					$html .= sprintf( '<label for="%s">%s', $field->id, sprintf( __( '%s', 'pqfw' ), $field->label ) );
					if ( $field->required ) {
						$html .= '<span class="required">*</span>';
					}
					$html .= '</label>';
					$html .= sprintf(
						'<input type="text" name="%s" class="form-control" id="%s" %s>',
						$this->getFieldName( $field->label ), $field->id, $field->required ? 'required' : ''
					);
					$html .= '</div>';
				}

				if ( 'TextArea' === $fieldType ) {
					$html .= sprintf( '<div class="form-group field_type_textarea %s">', $this->getFieldName( $field->label ) );
					$html .= sprintf( '<label for="%s">%s', $field->id, sprintf( __( '%s', 'pqfw' ), $field->label ) );
					if ( $field->required ) {
						$html .= '<span class="required">*</span>';
					}
					$html .= '</label>';
					$html .= sprintf(
						'<textarea type="text" name="%s" class="form-control" id="%s" %s></textarea>',
						$this->getFieldName( $field->label ), $field->id, $field->required ? 'required' : ''
					);
					$html .= '</div>';
				}

				if ( 'Dropdown' === $fieldType ) {
					$html .= sprintf( '<div class="form-group field_type_dropdown %s">', $this->getFieldName( $field->label ) );
					$html .= sprintf( '<label for="%s">%s', $field->id, sprintf( __( '%s', 'pqfw' ), $field->label ) );
					if ( $field->required ) {
						$html .= '<span class="required">*</span>';
					}
					$html .= '</label>';
					$html .= sprintf(
						'<select name="%s" class="form-control" id="%s" %s>',
						$this->getFieldName( $field->label ), $field->id, $field->required ? 'required' : ''
					);
					foreach ( $field->options as $option ) {
						$html .= sprintf( '<option value="%s">%s</option>', $option->value, $option->text );
					}
					$html .= '</select>';
					$html .= '</div>';
				}

				if ( 'RadioButtons' === $fieldType ) {
					$html .= sprintf( '<div class="form-group field_type_radiobuttons %s">', $this->getFieldName( $field->label ) );
					$html .= sprintf( '<label for="%s">%s', $field->id, sprintf( __( '%s', 'pqfw' ), $field->label ) );

					if ( $field->required ) {
						$html .= '<span class="required">*</span>';
					}
					$html .= '</label>';

					foreach ( $field->options as $option ) {
						$html .= sprintf( '<label for="%s">', $option->key );
							$html .= sprintf(
								'<input type="radio" name="%s" id="%s" value="%s" %s>',
								$this->getFieldName( $field->label ), $option->key, $option->value, $field->required ? 'required' : ''
							);
						$html .= sprintf( '%s</label>', $option->text );
					}
					$html .= '</div>';
				}

				if ( 'Checkboxes' === $fieldType ) {
					$html .= sprintf( '<div class="form-group field_type_checkbox %s">', $this->getFieldName( $field->label ) );
					$html .= sprintf( '<label for="%s">%s', $field->id, sprintf( __( '%s', 'pqfw' ), $field->label ) );
					if ( $field->required ) {
						$html .= '<span class="required">*</span>';
					}
					$html .= '</label>';

					foreach ( $field->options as $option ) {
						$html .= sprintf( '<label for="%s">', $option->key );
							$html .= sprintf(
								'<input type="checkbox" name="%s" id="%s" value="%s" %s />',
								$this->getFieldName( $field->label ), $option->key, $option->value, $field->required ? 'required' : ''
							);
						$html .= sprintf( '%s</label>', $option->text );
					}
					$html .= '</div>';
				}

				if ( 'Rating' === $fieldType ) {
					$html .= sprintf( '<div class="form-group field_type_rating %s">', $this->getFieldName( $field->label ) );
					$html .= sprintf( '<label for="%s">%s', $field->id, sprintf( __( '%s', 'pqfw' ), $field->label ) );
					if ( $field->required ) {
						$html .= '<span class="required">*</span>';
					}
					$html .= '</label>';
					$html .= '<input name="rating" type="range" value="2" step="0.25" id="backing5" style="display:none">';
					$html .= '<div
						class="rateit bigstars"
						data-rateit-starwidth="32"
						data-rateit-starheight="32"
						data-rateit-backingfld="#backing5"
						data-rateit-resetable="false" 
						data-rateit-ispreset="true"
						data-rateit-min="0"
						data-rateit-max="5"
					>
					</div>';
					$html .= '</div>';
				}

				if ( 'DatePicker' === $fieldType ) {
					$html .= sprintf( '<div class="form-group field_type_datepicker %s">', $this->getFieldName( $field->label ) );
					$html .= sprintf( '<label for="%s">%s', $field->id, sprintf( __( '%s', 'pqfw' ), $field->label ) );
					if ( $field->required ) {
						$html .= '<span class="required">*</span>';
					}
					$html .= '</label>';
					$html .= sprintf(
						'<input type="date" name="%s" id="%s" %s>',
						$this->getFieldName( $field->label ), $field->id, $field->required ? 'required' : ''
					);
					$html .= '</div>';
				}

				if ( 'Download' === $fieldType ) {
					$html .= sprintf( '<div class="form-group field_type_image_upload %s">', $this->getFieldName( $field->text ) );
						$html .= sprintf(
							'<input type="file" name="image_upload" id="image_upload"  multiple="false" accept=".png, .jpg, .jpeg, .gif" %s/>',
							$field->required ? 'required' : ''
						);
					$html .= '</div>';
				}
			}
		}

		$html .= '<div class="pqfw-form-field pqfw-submit">';
			$html .= '<input
				type="submit"
				id="rsrfqfwc_submit"
				name="rsrfqfwc_submit"
				value="' . esc_html__( 'Submit Query', 'pqfw' ) . '"
				class="submit"
			/>';
			$html .= '<div class="loading-spinner"></div>';
			$html .= sprintf( '<input type="hidden" id="pqfw_form_nonce_field" name="_nonce" value="%s" />', wp_create_nonce( 'pqfw_form_nonce_action' ) );
		$html .= '</div>';

		$html .= '<div class="form-group">';
			$html .= '<div class="pqfw-form-response-status"></div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Picks a specific value from collection of values.
	 *
	 * @since 2.0.7
	 *
	 * @param string $name   The value name to pick.
	 * @param array  $values The collection of values.
	 * @return mixed         The picked value.
	 */
	private function getValue( $name, $values ) {
		$data = null;
		foreach ( $values as $data ) {
			if ( $name === $data->name ) {
				$data = $data->value;
				break;
			}
		}
		return $data;
	}

	/**
	 * Save meta values based on the meta key.
	 *
	 * @since 2.0.12
	 *
	 * @param  mixed $values  The value to be saved.
	 * @param  int   $post_id The post id to update post meta.
	 * @return void
	 */
	private function saveMetaValues( $values, $post_id ) {
		foreach ( $values as $data ) {
			// Skipping the main fields to save as meta value.
			if ( 'name' === $data->name || 'content' === $data->name || '_nonce' === $data->name ) {
				continue;
			}

			// on the previous version meta fields name are can't be as the form builder fields name.
			$meta_key = '';
			switch ( $data->name ) {
				case 'designation':
					$meta_key = 'gs_t_client_design';
					break;
				case 'organization':
					$meta_key = 'gs_t_client_company';
					break;
				case 'email':
					$meta_key = 'gs_t_client_email';
					break;
				case 'rating':
					$meta_key = 'gs_t_rating';
					break;
				case 'address':
					$meta_key = 'gs_t_client_location';
					break;
				case 'phone/mobile':
					$meta_key = 'gs_t_client_phone';
					break;
				case 'video_url':
					$meta_key = 'gs_t_video_url';
					break;
				case 'website_url':
					$meta_key = 'gs_t_website_url';
					break;
			}

			if ( $meta_key ) {
				update_post_meta( $post_id, $meta_key, sanitize_text_field( $data->value ) );
			}
		}
	}

	/**
	 * Checks required fields value.
	 *
	 * @since 2.0.12
	 *
	 * @param  array $values The values to check the corresponding required fields.
	 * @return WP_JSON       WordPress json response.
	 */
	private function checkForRequiredFields( $values ) {
		$errors = [];
		$required = get_option( 'pqfw_form_required_fields', [] );

		if ( empty( $required ) ) {
			return;
		}

		$checks = [];
		foreach ( $values as $field ) {
			$checks[ $field->name ] = $field->value;
		}

		foreach ( $required as $item ) {
			if ( empty( trim( $checks[ $item ] ) ) ) {
				$name = ucfirst( $this->dGenerateFieldName( $item ) );
				$errors[] = "{$name} " . __( 'is required field.', 'pqfw' );
			}
		}

		if ( $errors ) {
			wp_send_json_error( [ 'errors' => $errors ] );
		}
	}

	/**
	 * Handles form media uploads.
	 *
	 * @since 2.0.12
	 *
	 * @param  int $post_id The post id to save the media as featured image.
	 * @return void
	 */
	public function handleMediaUpload( $post_id ) {
		if ( ! empty( $_FILES['image'] ) ) {
			$extension = pathinfo( $_FILES['image']['name'], PATHINFO_EXTENSION );
			$formats   = [ 'png', 'jpg', 'jpeg', 'gif' ];

			if ( ! in_array( $extension, $formats, true ) ) {
				wp_send_json_error( [ 'message' => __( 'Invalid file format.', 'pqfw' ) ] );
			}

			$attach_id = media_handle_upload( 'image', $post_id );
			update_post_meta( $post_id, '_thumbnail_id', $attach_id );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @return json
	 */
	public function getFormatted() {
		$fields = [];
		foreach ( $this->get_form_markup() as $field ) {
			$fields[ $this->getFieldName( $field->text ) ] = [
				'name'     => $this->getFieldName( $field->text ),
				'required' => wp_validate_boolean( $field->required )
			];
		}
		return $fields;
	}
}