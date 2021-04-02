<?php

class Devllo_Events_WC_Tickets {

    public function __construct(){

        add_action('devllo_events_after_side_single_event', array($this, 'devllo_events_reg_button'));

        add_action('devllo_events_after_side_single_event', array($this, 'devllo_events_ticket_button'));


        add_action('init', array($this, 'do_output_buffer'));

        if (isset($_COOKIE['devllo_event_wc_post_id'] )){
        add_filter('woocommerce_product_get_price', array($this, 'woocommerce_product_get_price'), 10, 2);
        }
             

    }

    // Create Registration Button
    function devllo_events_reg_button(){
        global $post;
        $current_user = wp_get_current_user();
        $product_id = $post->ID; 
        $product = wc_get_product( $product_id );
        if ( wc_customer_bought_product( wp_get_current_user()->user_email, get_current_user_id(), $product_id ) ) {
            _e( 'You have alreacy purchased a ticket for this event.' );
        }
        

        if ( (!in_array( 'devllo-events-bookings/devllo-events-bookings.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) ) {
        ?>
        <form method="post" style="padding: 10px;"> 
        <input type="submit" name="devllo_attend_event" class="button" value="<?php echo __('Purchase Event Ticket', 'devllo-events-woocommerce');?>" /> 
        </form>
        <?php 
        }
    
    }


    function devllo_events_ticket_button(){
        if ( (in_array( 'devllo-events-bookings/devllo-events-bookings.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) ) {
            $payment_gateway = get_option('devllo-events-bookings-payment-radio');

            if ($payment_gateway == "woocommerce"){ 

                global $post;

                $product_id = $post->ID;
        
                if(isset($_POST['devllo_attend_event'])) { 
                    if( get_post_type() == 'devllo_event' ) {

                    $value = $post->ID;
        
                    setcookie("devllo_event_wc_post_id", $value, time()+100, '/');
                    $url = '/cart/?add-to-cart=' .$product_id;
                    wp_redirect($url);
                    }
                }

            }
        
        } else 
    
        {

            global $post;

            $product_id = $post->ID;

            if(isset($_POST['devllo_attend_event'])) { 
                
                if( get_post_type() == 'devllo_event' ) {

                $value = $post->ID;

                setcookie("devllo_event_wc_post_id", $value, time()+100, '/');
                $url = '/cart/?add-to-cart=' .$product_id;
                wp_redirect($url);
                }
            }
        }
    }

   

    function woocommerce_product_get_price( $price, $product ) {
        global $post;
        if ( ! is_admin() ) {

        //  $product_id = $post->ID; 
        if (isset($_COOKIE['devllo_event_wc_post_id'] )){

            $post_id = $_COOKIE['devllo_event_wc_post_id']; 
        
            if ($product->get_id() == $post_id ) {
                $price = get_post_meta($product->get_id(), "devllo_event_price_key", true); 
            }
            return $price;
        }
        
        } 
    }

    function do_output_buffer() {
            ob_start();
    }
}

new Devllo_Events_WC_Tickets();