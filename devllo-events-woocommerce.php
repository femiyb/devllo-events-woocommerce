<?php
/*
    Plugin Name: WooCommerce Integration for Devllo Events Tickets
    Plugin URI: https://devlloplugins.com/
    Description: Purchase Events Tickets with WooCommerce
    Author: Devllo Plugins
    Version: 0.2.1
    Author URI: https://devllo.com/
    Text Domain: devllo-events-woocommerce
    Domain Path: /languages
 */


require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-license.php';

require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-ticket.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-settings.php';



add_filter( 'register_post_type_args', 'add_hierarchy_support', 10, 2 );
function add_hierarchy_support( $args, $post_type ){

    if ($post_type === 'product') { // <-- enter desired post type here

        $args['hierarchical'] = true;
        $args['supports'] = array_merge($args['supports'], array ('page-attributes') );
    }

    return $args;
}
