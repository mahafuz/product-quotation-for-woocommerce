<?php
namespace PQFW\Addons\Contactform;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Knot {
	public static function init() {
		$self = new self();

		if ( ! class_exists( 'WPCF7_Submission' ) ) {
			return;
		}

		add_action( 'wpcf7_before_send_mail', [ $self, 'handle' ] );
	}

	public function handle( $cf7 ) {
		$cf7_id = $cf7->id();
		$manager = \WPCF7_FormTagsManager::get_instance();
		die( ddump( $manager->normalize( $cf7->prop('form') ) ) );
		// die( ddump( $cf7->replace_all_form_tags() ) );
		// die(ddump( $cf7->get_properties() ));
		// $form_data = Mapper::get_data( $cf7 );
	}
}
