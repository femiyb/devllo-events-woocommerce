<?php

if( !class_exists('WPUpdatrPlugins') ){
    require_once plugin_dir_path( __FILE__ ).'/class.plugin-wp-updatr.php';
  }

  use WPUpdatrPlugins as devlloEventsWoocommerce;

  new devlloEventsWoocommerce\WPUpdatrPlugins( $license_key, $product_key );

  // License Key

  new myPluginAlias\WPUpdatrPlugins( get_option( 'devlloEventsWoocommerce_api_key' ), $product_key );

  // Plugin Product Key
  new myPluginAlias\WPUpdatrPlugins( get_option( 'devlloEventsWoocommerce_api_key' ), 'ELP-d967699a40bdaef97ef84c7f32d0d1' );

