<?php

if( !class_exists('WPUpdatrPlugins') ){
    require_once plugin_dir_path( __FILE__ ).'class.plugin-wp-updatr-products.php';
}

use WPUpdatrPlugins as devlloEventsWoocommerce;

$license_key =  get_option( 'devllo-wc-license-key');

$license = new devlloEventsWoocommerce\WPUpdatrPlugins( $license_key, 'ELP-d967699a40bdaef97ef84c7f32d0d1' );

$object = $license->verify_license();

// var_dump($object);
