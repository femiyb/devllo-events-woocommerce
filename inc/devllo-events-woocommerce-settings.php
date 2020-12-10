<?php

//

add_action('devllo_events_admin_menu_item', 'devllo_events_woocommerce_menu_item');

function devllo_events_woocommerce_menu_item(){
    add_submenu_page( 'edit.php?post_type=devllo_event', __('WC Integratiom', 'devllo-events-woocommerce'), __('WC Integration', 'devllo-events-woocommerce'), 'manage_options', 'devllo-events-woocommerce-dashboard', 'devllo_events_woocommerce_settings_content'  ); 
}

register_setting( 'devllo-events-woocommerce-options', 'devllo-wc-license-key' );

function devllo_events_woocommerce_settings_content(){
?>

<form method="post" action="options.php">

<?php

       /*
        if( $active_tab == 'devllo_events_woocommerce_options' ) {
          settings_fields( 'devllo-events-woocommerce-options' );
          do_settings_sections( 'devllo-events-woocommerce-options' );
          */
           ?>

<?php $devllowclicensekey = get_option('devllo-wc-license-key');?>
License Key: 
<input name="devllo-wc-license-key" type="text" class="regular-text" value="<?php if (isset($devllowclicensekey)) { echo esc_attr($devllowclicensekey); }?>">

<?php submit_button(); ?>		
</form>
<?php
}
