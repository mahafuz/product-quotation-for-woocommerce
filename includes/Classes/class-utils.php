<?php


namespace PQFW\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Database Utils
 *
 *
 * @author      Mahafuz
 * @package     PQFW
 * @since       1.0.0
 */
class Utils {

	/**
	 * Entries table name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private $entries = 'pqfw_entries';

	/**
	 * Retrive the entries table name.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_table_name() {

		global $wpdb;

		return esc_attr( $wpdb->prefix . $this->entries );

	}

	/**
	 * Insert data on the db.
	 *
	 * @param array $data submitted data.
	 * @param array $format data formating.
	 *
	 * @return int insert_id
	 * @since 1.0.0
	 */
	public function insert( $data, $format ) {

		if ( empty( $data ) ) {
			return;
		}

		global $wpdb;

		$wpdb->insert(
			self::get_table_name(),
			$data,
			$format
		);

		return absint($wpdb->insert_id);

	}

	/**
	 * Delete an entry
	 *
	 * @param int $entry_id
	 *
	 * @return int|bool
	 */
	public function delete( $entry_id ) {

		global $wpdb;

		$table = self::get_table_name();

		$deleted = $wpdb->delete(
			$table,
			[
				'id' => $entry_id,
			], [ '%d' ]
		);

		return $deleted;

	}

	/**
	 * Soft delete an entry
	 *
	 * @param int $entry_id
	 *
	 * @return int|bool
	 */
	public function soft_delete( $entry_id ) {

		global $wpdb;

		$table = self::get_table_name();

		$deleted = $wpdb->update(
			$table,
			array ( 'status' => 'trash' ),
			[ 'id' => $entry_id ],
			[ '%s' ]
		);

		return $deleted;

	}

	/**
	 * Restore an entry
	 *
	 * @param int $entry_id
	 *
	 * @return int|bool
	 */
	public function restore( $entry_id ) {

		global $wpdb;

		$table = self::get_table_name();

		$restored = $wpdb->update(
			$table,
			array ( 'status' => 'publish' ),
			[ 'id' => $entry_id ],
			[ '%s' ]
		);

		return $restored;

	}

	/**
	 * Fetch all data from data.
	 *
	 *
	 * @param string $email Email.
	 *
	 * @return int|false The user's ID on success, and false on failure.
	 * @since 1.0.0
	 *
	 */
	public function fetch_entries( $count, $offset, $status = 'publish' ) {

		global $wpdb;

		$table = self::get_table_name();

		$entries = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $table WHERE status = %s LIMIT %d OFFSET %d ",
				$status,
				$count,
				$offset
			)
		);

		if ( ! $entries ) {
			return [];
		}

		return $entries;

	}

	/**
	 * Fetch single entry from DB.
	 *
	 *
	 * @param string $id Entry ID.
	 *
	 * @return int|false The entry ID on success, and false on failure.
	 * @since 1.0.0
	 *
	 */
	public function fetch_entry( $id ) {

		global $wpdb;

		$table = self::get_table_name();

		$entry = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table WHERE id = %d",
				$id
			)
		);

		if ( ! $entry ) {
			return false;
		}

		return $entry;

	}

	/**
	 * Get the number of entries count.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function count_entries( $status = 'publish' ) {

		global $wpdb;

		$table = self::get_table_name();

		return (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT count(id) FROM $table WHERE status = %s",
				$status
			)
		);

	}

	public function get_status( $request ) {
		return isset( $request['pqfw-entries'] ) && ! empty( $request['pqfw-entries'] ) ? esc_attr( $request['pqfw-entries'] ) : 'publish';
	}

	/**
	 * Sanitize phone number.
	 * Allows only numbers and "+" (plus sign).
	 *
	 * @param string $phone Phone number.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function sanitize_phone_number( $phone ) {

		return preg_replace( '/[^\d+]/', '', $phone );

	}

	/**
	 * Determines whether the given email exists.
	 *
	 *
	 * @param string $email Email.
	 *
	 * @return int|false The user's ID on success, and false on failure.
	 * @since 1.0.0
	 *
	 */
	public function email_exists( $email, $id ) {

		global $wpdb;

		$table = self::get_table_name();

		$user = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $table WHERE email = %s AND product_id = %s LIMIT 1",
				$email,
				$id
			)
		);

		if ( ! $user ) {
			return false;
		}

		return $user;

	}

	/**
	 * Validate user data.
	 *
	 * @param int $quantity
	 * @param string $fullname
	 * @param string $email
	 *
	 * return \WP_Error
	 */
	public function validate( $fields ) {
		$errors = new \WP_Error();

		$requiredFields = [
			'fullname',
			'email'
		];

		foreach ( $requiredFields as $required ) {
			if ( empty( $fields[ $required ] ) ) {
				$errors->add( 'field', sprintf( '%s %s', $required, __( 'is required.', 'pqfw' ) ) );
			}
		}

		if ( $errors->has_errors() ) {
			return $errors;
		}

		if ( strlen( $fields['fullname'] ) < 4 ) {
			$errors->add( 'username_length', __( 'Username too short. At least 4 characters is required', 'pqfw' ) );
		}

		if ( ! validate_username( $fields['fullname'] ) ) {
			$errors->add( 'username_invalid', __( 'Sorry, the username you entered is not valid', 'pqfw' ) );
		}

		if ( ! is_email( $fields['email'] ) ) {
			$errors->add( 'email_invalid', __( 'Email is not valid', 'pqfw' ) );
		}

		// if ( self::email_exists( $email, $product_id ) ) {
		// 	$errors->add( 'email', __( 'You\'ve already submitted a quotation with this email.', 'pqfw' ) );
		// }

		return $errors;
	}
}