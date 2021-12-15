<?php

class Devllo_WooCommerce_License {

    public function __construct(){     
        add_action( 'devllo_events_admin_menu_item', array($this, 'devllo_events_woocommerce_menu_item' ));

        add_action( 'admin_init', array($this, 'init_settings'));
        add_action( 'admin_notices', array($this, 'license_admin_notice'));
    }


        function init_settings() {
            register_setting( 'hello-settings-options', 'license-api-key' );
            register_setting( 'hello-settings-options', 'license-email' );
        }


        function devllo_events_woocommerce_menu_item(){
          add_submenu_page( 'edit.php?post_type=devllo_event', __('WC Integratiom', 'devllo-events-woocommerce'), __('WC Integration', 'devllo-events-woocommerce'), 'manage_options', 'devllo-events-woocommerce-dashboard', array($this, 'settings_page')); 
      }
 
        function license_admin_notice() {
            $licenseapikey = get_option('license-api-key');
            $licenseemail = get_option('license-email');
            if ( !function_exists( 'get_current_screen' ) ) { 
                require_once ABSPATH . '/wp-admin/includes/screen.php'; 
            } 
            $my_current_screen = get_current_screen();

            if ( isset( $my_current_screen->base ) && 'devllo_event_page_devllo-events-woocommerce-dashboard' === $my_current_screen->base ) {

                if (!$licenseapikey || !$licenseemail){
                    ?>
                                <div class="update-nag notice">
                                    <?php _e('Please Enter Your License Key For Updates', 'plugin-domain') ; ?>
                                </div>
                    <?php
                }
            }

        }

        function settings_page(){ 
          
          ?>
        
            <form method="post" action="options.php">

            <?php
            settings_fields( 'hello-settings-options' );
            do_settings_sections( 'hello-settings-options' );

        _e('<h3>License Settings</h3>', 'plugin-domain');

        $licenseapikey = get_option('license-api-key');
        $licenseemail = get_option('license-email');

        $license_manager = new WP_License_It_Client(
            'hello',
            'Hello',
            'hello-text',
            'https://devlloplugins.com/api/wp-license-it-api/v1', // api_url
            'plugin',
            __FILE__,
            );
        
            // Get License Status
            $status = $license_manager->get_license_status();
        ?>

            <h4>
            <?php _e('License Key:', 'plugin-domain'); ?> <br />
            <input name="license-api-key" type="text" class="regular-text" value="<?php if (isset($licenseapikey)) { echo esc_attr($licenseapikey); }?>"> <br/>
            </h4>

            <h4>
            <?php _e('License Email:', 'plugin-domain'); ?> <br />
            <input name="license-email" type="text" class="regular-text" value="<?php if (isset($licenseemail)) { echo esc_attr($licenseemail); }?>"> <br/>
            </h4>
            
            <div><h4>
            <?php _e('License Status:', 'plugin-domain'); ?>

        <?php 
            if ($status){
            echo $status;
            }
            ?>
            </h4></div>
            <?php
        
        submit_button();?>
            </form> <?php

        }
}

new Devllo_WooCommerce_License;