<?php

    // Create Registration Button
    function devllo_events_reg_button(){
        global $post;
        $current_user = wp_get_current_user();
        $product_id = get_post_meta( get_the_ID(), 'devllo_events_select_ticket_key', true ); 
        $product = wc_get_product( $product_id );
        if ( wc_customer_bought_product( wp_get_current_user()->user_email, get_current_user_id(), $product_id ) ) {
            _e( 'You have alreacy purchased a ticket for this event.' );
        }
        
        if ($product_id){
        ?>
        <form method="post" style="padding: 10px;"> 
        <input type="submit" name="devllo_attend_event" class="button" value="<?php echo __('Purchase Event Ticket', 'devllo-events-woocommerce');?>" /> 
        </form>
        <?php }
    }

    add_action ('devllo_events_after_side_single_event', 'devllo_events_reg_button');

    add_action ('devllo_events_after_side_single_event', 'devllo_events_ticket_button');


    function devllo_events_ticket_button(){
        $product_id = get_post_meta( get_the_ID(), 'devllo_events_select_ticket_key', true ); 

        if(isset($_POST['devllo_attend_event'])) { 
            WC()->cart->add_to_cart( $product_id );
            // wc_get_checkout_url();
            $url = wc_get_checkout_url();
            wp_redirect($url);
            exit; 
        }
    }

    add_action('init', 'do_output_buffer');
    function do_output_buffer() {
            ob_start();
    }