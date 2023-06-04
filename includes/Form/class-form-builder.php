<?php

namespace PQFW\Form;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Handles testimonial submission form.
 *
 * @since 2.0.7
 */
class Form_Builder {

	/**
	 * Class constructor.
	 *
	 * @since 2.0.5
	 */
	public function __construct() {
		add_shortcode( 'pqfw_quote_form', [ $this, 'render' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'wp_ajax_pqfw_form_data', [ $this, 'post_data' ] );
		add_action( 'wp_ajax_nopriv_pqfw_form_data', [ $this, 'post_data' ] );
		add_action( 'admin_menu', [ $this, 'registerMenus' ] );
		add_action( 'in_admin_header', [ $this, 'remove_notice' ], 1000 );
	}

	/**
	 * Register form menu.
	 *
	 * @since 2.0.5
	 */
	public function registerMenus() {
		add_submenu_page(
			'edit.php?post_type=pqfw_quotations',
			__( 'Product Quotation Form Builder', 'pqfw' ),
			__( 'Form', 'pqfw' ),
			'manage_options',
			'pqfw-form-builder',
			[ $this, 'displayForm' ]
		);
	}

	/**
	 * Displaying the 'Form' editor page.
	 *
	 * @since 2.0.5
	 */
	public function displayForm() {
		echo '<div id="pqfw-form-builder"></div>';
	}

	/**
	 * Enqueue scripts for the form shortcode.
	 *
	 * @since 2.0.5
	 * @return void
	 */
	public function scripts() {
		$screen = get_current_screen();

		if ( 'pqfw_quotations_page_pqfw-form-builder' !== $screen->id ) {
			return;
		}

		$dependencies = require_once PQFW_PLUGIN_PATH . 'includes/Form/build/index.asset.php';

		wp_enqueue_style(
			'font-awesome4.3',
			'//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'
		);

		wp_register_script(
			'pqfw-form-builder',
			PQFW_PLUGIN_URL . 'includes/Form/build/index.js',
			$dependencies['dependencies'],
			$dependencies['version'],
			true
		);

		wp_localize_script(
			'pqfw-form-builder',
			'PQFW_FORM_SCRIPT',
			[
				'nonce'   => wp_create_nonce( 'pqfw_form_builder_nonce' ),
				'home'    => home_url(),
				'restUrl' => rest_url(),
			]
		);

		wp_enqueue_script( 'pqfw-form-builder' );

		wp_enqueue_style(
			'pqfw-form-builder',
			PQFW_PLUGIN_URL . 'includes/Form/build/index.css',
			[],
			$dependencies['version'],
			'all'
		);
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
	 * Remove all notice in setup wizard page
	 */
	public function remove_notice() {
		if ( isset( $_GET['page'] ) && 'pqfw-form-builder' === sanitize_text_field( $_GET['page'] ) ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
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
	 * Render form frontend.
	 *
	 * @since 2.0.7
	 */
	public function render() {
		$formData = pqfw()->formApi->getFormMarkup();
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
	 * Form ajax action callback.
	 * Responsible for saving the form data.
	 *
	 * @since  2.0.12.
	 */
	public function post_data() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'pqfw-form-action' ) ) {
			wp_send_json_error( [ 'message' => __( "You're not allowed to submit the form", 'pqfw' ) ] );
		}

		if ( empty( $_POST['values'] ) ) {
			wp_send_json_error( [ 'message' => __( "Invalid data.", 'pqfw' ) ] );
		}

		$values = json_decode( wp_unslash( $_POST['values'] ) );

		if ( empty( $values ) ) {
			wp_send_json_error( [ 'message' => __( "Invalid data.", 'pqfw' ) ] );
		}

		// Check for required fields.
		$this->checkForRequiredFields( $values );

		$title   = sanitize_text_field( $this->getValue( 'name', $values ) );
		$content = wp_kses_post( $this->getValue( 'content', $values ) );

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$new_post = [
			'post_type'    => 'gs_testimonial',
			'post_status'  => 'pending',
			'post_title'   => $title,
			'post_content' => $content,
		];

		$post_id = wp_insert_post( $new_post );

		$this->handleMediaUpload( $post_id );
		$this->saveMetaValues( $values, $post_id );

		$message = pqfw()->db->formSettings->get( 'message', __( 'Thanks! Sent for admin approval.', 'pqfw' ) );
		wp_send_json_success( [ 'message' => $message ] );
	}
}