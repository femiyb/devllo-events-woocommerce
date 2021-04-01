<?php
/*
    Plugin Name: WooCommerce Integration for Devllo Events
    Plugin URI: https://devlloplugins.com/
    Description: Purchase Events Tickets with WooCommerce
    Author: Devllo Plugins
    Version: 0.2.3
    Author URI: https://devllo.com/
    Text Domain: devllo-events-woocommerce
    Domain Path: /languages
 */
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

 
if ( (in_array( 'devllo-events/devllo-events.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) &&  (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) ) {

add_action('woocommerce_loaded' , function (){
 
require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-license.php';

require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-ticket.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/devllo-events-woocommerce-add-to-cart-function.php';

});

/*
add_filter( 'register_post_type_args', 'add_hierarchy_support', 10, 2 );
function add_hierarchy_support( $args, $post_type ){

    if ($post_type === 'product') { // <-- enter desired post type here

        $args['hierarchical'] = true;
        $args['supports'] = array_merge($args['supports'], array ('page-attributes') );
    }

    return $args;
}*/



}else
{
    function devllo_events_pmpro_admin_notice(){
    echo '<div class="notice notice-error is-dismissible"><p> The WooCommerce integration add on for Devllo Events is installed but the Devllo Events plugin and/or the WooCommerce plugin is not installed</p></div>';
    }
    add_action('admin_notices', 'devllo_events_pmpro_admin_notice');
}

