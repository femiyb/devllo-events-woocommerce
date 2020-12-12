<?php

//

add_action('devllo_events_admin_menu_item', 'devllo_events_woocommerce_menu_item');
add_action( 'admin_init', 'init_settings');


function devllo_events_woocommerce_menu_item(){
    add_submenu_page( 'edit.php?post_type=devllo_event', __('WC Integratiom', 'devllo-events-woocommerce'), __('WC Integration', 'devllo-events-woocommerce'), 'manage_options', 'devllo-events-woocommerce-dashboard', 'devllo_events_woocommerce_settings_content'  ); 
}

function init_settings() {

register_setting( 'devllo-events-woocommerce-options', 'devllo-wc-license-key' );

}

use WPUpdatrPlugins as devlloEventsWoocommerce;

$license = new myPluginAlias\WPUpdatrPlugins( get_option( 'devllo-wc-license-key', 'ELP-d967699a40bdaef97ef84c7f32d0d1' ));

$object = $license->verify_license();

var_dump($object);

function devllo_events_woocommerce_settings_content(){

    $active_tab = "devllo_events_woocommerce_options";
    $tab = filter_input(
        INPUT_GET, 
        'tab', 
        FILTER_CALLBACK, 
        ['options' => 'esc_html']
    );
    if( isset( $tab ) ) {
        $active_tab = $tab;
      } 
?>

<form method="post" action="options.php">

<?php

       
        if( $active_tab == 'devllo_events_woocommerce_options' ) {
          settings_fields( 'devllo-events-woocommerce-options' );
          do_settings_sections( 'devllo-events-woocommerce-options' );
          
           ?>

<?php $devllowclicensekey = get_option('devllo-wc-license-key');?>

License Key: 
<input name="devllo-wc-license-key" type="text" class="regular-text" value="<?php if (isset($devllowclicensekey)) { echo esc_attr($devllowclicensekey); }?>">

<?php submit_button();
}

 ?>		
</form>
<?php
}
