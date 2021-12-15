<?php
/*
    Plugin Name: WooCommerce Integration for Devllo Events
    Plugin URI: https://devlloplugins.com/
    Description: Purchase Events Tickets with WooCommerce
    Author: Devllo Plugins
    Version: 0.9
    Author URI: https://devllo.com/
    Text Domain: devllo-events-woocommerce
    Domain Path: /languages
 */


require_once plugin_dir_path( __FILE__ ) . 'inc/class.plugin-wp-updatr-products.php';

require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-ticket.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-settings.php';


if ( is_admin() ) {
    $license_manager = new WP_License_It_Client(
        'devllo-events-woocommerce', // plugin key
        'WooCommerce Integration for Devllo Events', // plugin name
        'devllo-events-woocommerce', // plugin domain
        'https://devlloplugins.com/api/wp-license-it-api/v1', // api_url
        'plugin', // Plugin or Theme
        __FILE__,
    );
}

add_filter( 'register_post_type_args', 'add_hierarchy_support', 10, 2 );
function add_hierarchy_support( $args, $post_type ){

    if ($post_type === 'product') { // <-- enter desired post type here

        $args['hierarchical'] = true;
        $args['supports'] = array_merge($args['supports'], array ('page-attributes') );
    }

    return $args;
}
