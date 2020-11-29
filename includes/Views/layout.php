<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if( ! isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'pqfw-options-page' ) exit;

use \PQFW\Database\Utils;

if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'delete' && isset( $_REQUEST['post'] ) && !empty( $_REQUEST['post'] ) ) {
//    ddd( $_REQUEST );

//    foreach ( $_REQUEST['post'] as $post ) {
//        Utils::soft_delete( (int)$post );
//    }
}

if( isset( $_REQUEST['pqfw-entry'] ) && !empty( $_REQUEST['pqfw-entry'] ) ) {
    include( __DIR__ . '/partials/entry-details.php' );
}else {
    include( __DIR__ . '/partials/entries.php' );
}

