<?php
namespace PQFW\Addons\Contactform;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Installer {
	public static function init() {
		$self = new self();
		$self->create_database();
		$self->saved_settings();
		$self->save_option();
	}

	public function create_database() {}

	public function saved_settings() {}

	public function save_option() {
		if ( get_option( PQFW_CF7_VERSION_NAME ) !== PQFW_CF7_VERSION ) {
			update_option( PQFW_CF7_VERSION_NAME, PQFW_CF7_VERSION );
		}
	}
}
