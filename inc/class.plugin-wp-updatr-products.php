<?php

if ( ! class_exists( 'WP_License_It_Client' ) ) {
 
    class WP_License_It_Client {

        /**
         * The API endpoint. Configured through the class's constructor.
         *
         * @var String  The API endpoint.
         */
        public $api_endpoint;
        
        /**
         * The product id (slug) used for this product on the License Manager site.
         * Configured through the class's constructor.
         *
         * @var int     The product id of the related product in the license manager.
         */
        public $product_id;
        
        /**
         * The name of the product using this class. Configured in the class's constructor.
         *
         * @var int     The name of the product (plugin / theme) using this class.
         */
        public $product_name;
        
        /**
         * The type of the installation in which this class is being used.
         *
         * @var string  'theme' or 'plugin'.
         */
        public $type;
        
        /**
         * The text domain of the plugin or theme using this class.
         * Populated in the class's constructor.
         *
         * @var String  The text domain of the plugin / theme.
         */
        public $text_domain;
        
        /**
         * @var string  The absolute path to the plugin's main file. Only applicable when using the
         *              class with a plugin.
         */
        public $plugin_file;

        public $product_api_key;



        public function __construct( $product_id, $product_name, $text_domain, $api_url, $type = 'plugin', $plugin_file = '' ) {
                            // Store setup data
                            $this->product_id = $product_id;
                            $this->product_name = $product_name;
                            $this->text_domain = $text_domain;
                            $this->api_endpoint = 'https://devlloplugins.com/api/wp-license-it-api/v1'; // api_url

                            $this->type = $type;
                            $this->plugin_file = $plugin_file;

                            add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_update' ) );
                            add_filter( 'plugins_api', array( $this, 'plugins_api_handler' ), 10, 3 );

        }

        /**
         * The filter that checks if there are updates to the theme or plugin
         * using the License Manager API.
         *
         * @param $transient    mixed   The transient used for WordPress theme updates.
         * @return mixed        The transient with our (possible) additions.
         */
        public function check_for_update( $transient ) {
 
            if ( empty( $transient->checked ) ) {
                return $transient;
            }
         
            if ( $this->is_update_available() ) {
                $info = $this->get_license_info();
         
         
                    // Plugin update
                    $plugin_slug = plugin_basename( $this->plugin_file );
         
                    $transient->response[$plugin_slug] = (object) array(
                        'new_version' => $info->version,
                        'package' => $info->package_url,
                        'slug' => $plugin_slug
                    );
                
            }
  
         
            return $transient;
        }


        /**
         * Makes a call to the WP License Manager API.
         *
         */

        private function call_api( $action, $params ) {
            
            $url = $this->api_endpoint . '/' . $action;
         
            // Append parameters for GET request
            $url .= '?' . http_build_query( $params );

            // Send the request
            $response = wp_remote_get( $url );
            if ( is_wp_error( $response ) ) {
                return false;
            }
      
            $response_body = wp_remote_retrieve_body( $response );
            $result = json_decode( $response_body );
             
            return $result;
        }


        /**
         * Checks the API response to see if there was an error.
         *
         * @param $response mixed|object    The API response to verify
         * @return bool     True if there was an error. Otherwise false.
         */
        private function is_api_error( $response ) {
            if ( $response === false ) {
                return true;
            }
        
            if ( ! is_object( $response ) ) {
                return true;
            }
        
            if ( isset( $response->error ) ) {
                return true;
            }
        
            return false;
        }


        public function get_license_info() {

            $licenseapikey = get_option('license-api-key');
            $licenseemail = get_option('license-email');
            $product_api_key = 'TLBuHS1Rr2dPeOTdsysmsnvM';

            if ( ! isset($licenseapikey ) || ! isset( $licenseemail ) ) {
                // User hasn't saved the license to settings yet. No use making the call.
                return false;
            }
         
            $info = $this->call_api(
                'info',
                array(
                    'p' => '712',
                    'k' => $product_api_key,
                    'e' => $licenseemail,
                    'l' => $licenseapikey,
                )
            );
         
            return $info;
        }


        public function get_license_status() {

            $licenseapikey = get_option('license-api-key');
            $licenseemail = get_option('license-email');
            $product_api_key = 'TLBuHS1Rr2dPeOTdsysmsnvM';

            if ( ! isset($licenseapikey ) || ! isset( $licenseemail ) ) {
                // User hasn't saved the license to settings yet. No use making the call.
                return false;
            }
         
            $status = $this->call_api(
                'status',
                array(
                    'p' => '712',
                    'k' => $product_api_key,
                    'e' => $licenseemail,
                    'l' => $licenseapikey,
                )
            );

           return $status;
         
            // if($status){
            //     print 'active';
            // } else {
            //     print 'inactive';
            // }
         //   return $status;
        }


        /**
         * Checks the license manager to see if there is an update available for this theme.
         *
         * @return object|bool  If there is an update, returns the license information.
         *                      Otherwise returns false.
         */
        public function is_update_available() {
            $license_info = $this->get_license_info();
            if ( $this->is_api_error( $license_info ) ) {
                return false;
            }
        
            if ( version_compare( $license_info->version, $this->get_local_version(), '>' ) ) {
                return $license_info;
            }
        
            return false;
        }

        /**
         * @return string   The theme / plugin version of the local installation.
         */
        private function get_local_version() {
                $plugin_data = get_plugin_data( $this->plugin_file, false );
                return $plugin_data['Version'];
            
        }

        /**
         * A function for the WordPress "plugins_api" filter. Checks if
         * the user is requesting information about the current plugin and returns
         * its details if needed.
         *
         * This function is called before the Plugins API checks
         * for plugin information on WordPress.org.
         *
         * @param $res      bool|object The result object, or false (= default value).
         * @param $action   string      The Plugins API action. We're interested in 'plugin_information'.
         * @param $args     array       The Plugins API parameters.
         *
         * @return object   The API response.
         */
        public function plugins_api_handler( $res, $action, $args ) {
            if ( $action == 'plugin_information' ) {
        
                // If the request is for this plugin, respond to it
                if ( isset( $args->slug ) && $args->slug == plugin_basename( $this->plugin_file ) ) {
                    $info = $this->get_license_info();
        
                    $res = (object) array(
                        'name' => isset( $info->name ) ? $info->name : '',
                        'version' => $info->version,
                        'slug' => $args->slug,
                        'download_link' => $info->package_url,
        
                        'tested' => isset( $info->tested ) ? $info->tested : '',
                        // 'requires' => isset( $info->requires ) ? $info->requires : '',
                        // 'last_updated' => isset( $info->last_updated ) ? $info->last_updated : '',
                        // 'homepage' => isset( $info->description_url ) ? $info->description_url : '',
        
                        'sections' => array(
                            'description' => $info->description,
                        ),
        
                        // 'banners' => array(
                        //     'low' => isset( $info->banner_low ) ? $info->banner_low : '',
                        //     'high' => isset( $info->banner_high ) ? $info->banner_high : ''
                        // ),
        
                        'external' => true
                    );

                    // Add change log tab if the server sent it
                    if ( isset( $info->changelog ) ) {
                        $res['sections']['changelog'] = $info->changelog;
                    }
        
                    return $res;
                }
            }
        
            // Not our request, let WordPress handle this.
            return false;
        }
        
    }


}
 
