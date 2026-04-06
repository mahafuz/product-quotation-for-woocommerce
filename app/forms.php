<?php
/**
 * Quotify forms class
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
class Forms {
	/**
	 * Class instance.
	 *
	 * @var Quotify\Forms
	 */
	private static $instance = null;

	/**
	 * Runs before load the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var Quotify\Forms
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Contains ajax requests for products.
	 *
	 * @var mixed
	 */
	private $controls;

	/**
	 * Initialize ajax actions.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->controls = \Quotify\Forms\Controls::init();

		add_action( 'quotify/templates/cart/form', [ $this, 'form' ] );
	}

	/**
	 * Generate default form controls.
	 *
	 * @return mixed
	 */
	public function generate() {
		return $this->controls->generate_fields();
	}

	/**
	 * Form html
	 *
	 * @since   1.0.0
	 */
	public function form() {
		$settings = quotify()->settings()->get();
		$classes  = [];

		if ( $settings['pqfw_form_default_design'] ) {
			$classes[] = 'use-pqfw-form-default-design';
		}
		if ( $settings['pqfw_floating_form'] ) {
			$classes[] = 'floating-form';
		}

		$classes = apply_filters( 'quotify/form/wrapper_class', $classes );
		?>
		<div id="pqfw-frontend-form-wrap" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="pqfw-form">
				<?php $this->get_form_html(); //phpcs:ignore ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get form type based on the addon and others configuration.
	 *
	 * @since 2.5.0
	 * @return string
	 */
	private function get_form_type() {
		return 'default';
	}

	/**
	 * Get form HTML based on integration type.
	 *
	 * @since 2.5.0
	 */
	private function get_form_html() {
		$form_type = $this->get_form_type();
		$form_id   = 0;

		switch ( $form_type ) {
			case 'cf7':
				if ( class_exists( '\QuotifyContact_Form_7\Database' ) ) {
					$form_id = absint( \QuotifyContact_Form_7\Database::get_setting( 'form_id' ) );
				}

				$shortcode = sprintf( '[contact-form-7 id="%d"]', $form_id );
				echo do_shortcode( $shortcode );
				break;

			default:
				ob_start();
					require_once QUOTIFY_PLUGIN_VIEWS . 'form/default.php';
				$form_html = ob_get_clean();
				echo \Quotify\Library\Helper::escape_html_form( $form_html );//phpcs:ignore
		}
	}
}
