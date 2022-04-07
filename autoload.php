<?php
/**
 * Handling plugin spl autoloader golobally
 *
 * @since   1.2.0
 * @package PQFW
 */

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

spl_autoload_register(function ( $class ) {
	$prefix   = 'PQFW\\';
	$base_dir = PQFW_PLUGIN_PATH . 'includes/';
	$len      = strlen( $prefix );

	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$class = substr( $class, $len );
	$arr           = explode( '\\', $class );
	$index         = absint( count( $arr ) - 1 );
	$fileName      = strtolower( $arr[ $index ] );
	$fileName      = str_replace( '_', '-', $fileName );
	$fileName      = 'class-' . $fileName;
	$arr[ $index ] = $fileName;

	$class = join( '\\', $arr );
	$file  = $base_dir . str_replace( '\\', '/', $class ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
});