<?php


namespace PQFW\Database;

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
    private static $entries = 'pqfw_entries';

    /**
     * Retrive the entries table name.
     *
     * @return string
     * @since 1.0.0
     */
    public static function get_table_name() {
        global $wpdb;
        return $wpdb->prefix . self::$entries;
    }

    /**
     * Insert data on the db.
     *
     * @since 1.0.0
     * @param array $data submitted data.
     * @param array $format data formating.
     * @return int insert_id
     */
    public static function insert($data, $format) {
        if( empty( $data ) ) return;

        global $wpdb;

        $wpdb->insert(
            self::get_table_name(),
            $data,
            $format
        );

        return $wpdb->insert_id;
    }

    /**
     * Fetch all data from data.
     *
     *
     * @since 1.0.0
     *
     * @param string $email Email.
     * @return int|false The user's ID on success, and false on failure.
     */
    public static function fetch_entries( $count, $offset ) {
        global $wpdb;

        $table = self::get_table_name();

        $entries = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table LIMIT %d OFFSET %d",
                $count,
                $offset
            )
        );

        if ( ! $entries ) {
            return false;
        }

        return $entries;
    }

    /**
     * Fetch single entry from DB.
     *
     *
     * @since 1.0.0
     *
     * @param string $id Entry ID.
     * @return int|false The entry ID on success, and false on failure.
     */
    public static function fetch_entry( $id ) {
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
     * @since 1.0.0
     *
     * @return int
     */
    public static function count_entries( $status = 'publish' ) {
        global $wpdb;

        $table = self::get_table_name();

        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(id) FROM $table WHERE status = %s",
                $status
            )
        );
    }

    /**
     * Sanitize phone number.
     * Allows only numbers and "+" (plus sign).
     *
     * @since 1.0.0
     * @param string $phone Phone number.
     * @return string
     */
    public static function sanitize_phone_number( $phone ) {
        return preg_replace( '/[^\d+]/', '', $phone );
    }

    /**
     * Determines whether the given email exists.
     *
     *
     * @since 1.0.0
     *
     * @param string $email Email.
     * @return int|false The user's ID on success, and false on failure.
     */
    public static function email_exists( $email, $id ) {
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
     * @param   int $quantity
     * @param   string $fullname
     * @param   string $email
     *
     * return \WP_Error
     */
    public static function validate( $quantity, $fullname, $email, $product_id ) {
        $errors = new \WP_Error;

        if ( empty( $fullname ) || empty( $email ) || empty( $quantity ) ) {
            $errors->add('field', __('Required form field is missing','pqfw'));
        }

        if ( strlen( $fullname ) < 4 ) {
            $errors->add('username_length', __('Username too short. At least 4 characters is required','pqfw'));
        }

        if ( !validate_username( $fullname ) ) {
            $errors->add('username_invalid', __('Sorry, the username you entered is not valid','pqfw'));
        }

        if ( !is_email( $email ) ) {
            $errors->add('email_invalid', __('Email is not valid','pqfw'));
        }

        if ( self::email_exists( $email, $product_id ) ) {
            $errors->add('email', __('You\'ve already submitted a quotation with this email.','pqfw'));
        }

        return $errors;
    }

}
