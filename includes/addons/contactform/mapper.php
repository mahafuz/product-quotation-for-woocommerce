<?php
namespace PQFW\Addons\Contactform;

use PQFW\Utils\Abstracts\Quotation_Mapper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mapper extends Quotation_Mapper {
	public static function get_data( $cf7 ) {
		if ( ! isset( $cf7->posted_data ) && class_exists( 'WPCF7_Submission' ) ) {
			$submission = \WPCF7_Submission::get_instance();

			if ( $submission ) {
				$quotation = array();
				$quotation['fields']              = $submission->get_posted_data();
				$title                          = ! empty( $quotation['fields']['your-name'] ) ? sanitize_text_field( $quotation['fields']['your-name'] ) : '';
				$quotation['title']             = apply_filters(
					'quotify/addons/cf7/quotation_title',
					sprintf( "%$1s - %$2s", $title )
				);
				$quotation['uploaded_files']    = $submission->uploaded_files();
				$quotation['WPCF7_ContactForm'] = $cf7;
				$cf7                       = (object) $quotation;
			}
		}

		return $cf7;	
	}
} 