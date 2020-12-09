<?php

//

add_action('devllo_events_admin_menu_item', 'devllo_events_woocommerce_menu_item');

function devllo_events_woocommerce_menu_item(){
    add_submenu_page( 'edit.php?post_type=devllo_event', __('WC Integratiom', 'devllo-events-woocommerce'), __('WC Integration', 'devllo-events-woocommerce'), 'manage_options', 'devllo-events-woocommerce-dashboard', 'devllo_events_woocommerce_settings'  ); 
}
?>

<h1>License Key</h1>