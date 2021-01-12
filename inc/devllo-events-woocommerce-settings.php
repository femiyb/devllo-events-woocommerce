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


if ($object == null){
  $license_status = "License Not Active";
  $license_class = "red";
}
else {
  $license_status = "License Active";
  $license_class = "green";

}


function devllo_events_woocommerce_settings_content(){
    
  global $license_status;
  global $license_class;


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

<h2 class="nav-tab-wrapper">
        <a style="color:<?php echo $license_class;?>" href="?post_type=devllo_event&page=devllo-events-woocommerce-dashboard" class="nav-tab <?php echo $active_tab == 'devllo_events_options' ? 'nav-tab-active' : ''; ?>"><?php _e('License', 'devllo-events'); ?></a>
</h2>

    <form method="post" action="options.php">

    <?php

       
        if( $active_tab == 'devllo_events_woocommerce_options' ) {
          settings_fields( 'devllo-events-woocommerce-options' );
          do_settings_sections( 'devllo-events-woocommerce-options' );
          
           ?>

    <?php $devllowclicensekey = get_option('devllo-wc-license-key');?>

    <table class="table">
    <tr>

    <th style="text-align: left;">License Key: </th>
    <th><input name="devllo-wc-license-key" type="text" class="regular-text" value="<?php if (isset($devllowclicensekey)) { echo esc_attr($devllowclicensekey); }?>"></th>
    </tr>
    </table>
    <div style="color:<?php echo $license_class;?>"><strong>
    <?php 
    
    echo $license_status;
    ?> </strong></div>
    <?php
    
    // Submit
    submit_button();
}

 ?>		
</form>
<?php
}
