<?php


// Add Event Ticket CheckBox To WooCommerce

add_action( 'add_meta_boxes', 'devllo_events_woocommerce_checkbox_function' );
function devllo_events_woocommerce_checkbox_function() {
   add_meta_box('devllo_events_woocommerce_id','Check to make this an event ticket product', 'devllo_events_woocommerce_callback_function', 'product', 'side', 'high');
}

function devllo_events_woocommerce_callback_function() {
    wp_nonce_field( 'devllo_event_woocommerce_inner_custom_box', 'devllo_event_woocommerce_inner_custom_box_nonce' );


    $devlloeventswooticket = get_post_meta( get_the_ID(), 'devllo_events_woocommerce_ticket_key', true );
    ?>

    <?php _e('Set as Event Ticket Product:', 'devllo-events-woocommerce'); ?> <input type="checkbox" id="devllo_events_woocommerce_ticket_field" name="devllo_events_woocommerce_ticket_field" value="yes" <?php echo (($devlloeventswooticket=='yes') ? 'checked="checked"': '');?>/><br/>
    <?php
}

// Save checkbox
add_action('save_post', 'save_devllo_event_woocommerce_ticket_checkbox'); 

function save_devllo_event_woocommerce_ticket_checkbox($product_id){

       // Add nonce for security and authentication.
    if ( ! isset( $_POST['devllo_event_woocommerce_inner_custom_box_nonce'] ) ) {
        return $product_id;
    }
    
    $nonce = $_POST['devllo_event_woocommerce_inner_custom_box_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'devllo_event_woocommerce_inner_custom_box' ) ) {
        return $product_id;
    }
    
    /*
     * If this is an autosave, our form has not been submitted,
     * so we don't want to do anything.
     */
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $product_id;
    }

    //Sanitize fields
    if (isset($_POST['devllo_events_woocommerce_ticket_field'])){
        $devllo_event_woocommerce_ticket = sanitize_key($_POST['devllo_events_woocommerce_ticket_field']);
    }
    
    //Update and save fields

    if (isset($_POST['devllo_events_woocommerce_ticket_field'])){
        update_post_meta( $product_id, 'devllo_events_woocommerce_ticket_key', $devllo_event_woocommerce_ticket );
    }else{
        delete_post_meta($product_id, 'devllo_events_woocommerce_ticket_key');
    }
}


// Add Product(Ticket) Select Box to Event Posts

add_action( 'add_meta_boxes', 'devllo_events_select_ticket_function' );
function devllo_events_select_ticket_function() {
   add_meta_box('devllo_events_select_ticket_id','Select Event Ticket', 'devllo_events_select_ticket_callback_function', 'devllo_event', 'side', 'high');
}

function devllo_events_select_ticket_callback_function() {
    global $products;
    global $product_id;
    wp_nonce_field( 'devllo_event_select_ticket_inner_custom_box', 'devllo_event_select_ticket_inner_custom_box_nonce' );


    $devlloeventsselectticket = get_post_meta( get_the_ID(), 'devllo_events_select_ticket_key', true );
    

    $args = array(
                'post_type' => 'product',
                'meta_key' => 'devllo_events_woocommerce_ticket_key',
                'meta_value'	=> 'yes',
				'name' => 'devllo_events_select_ticket_field', 
				'show_option_none' => __( '— Select —' ), 
				'option_none_value' => '0', 
                'selected' => get_post_meta( get_the_ID(), 'devllo_events_select_ticket_key', true ),
            );
              //  add_filter( 'list_pages', 'my_list_pages_custom_field', 10, 2 );
                wp_dropdown_pages($args);

               // remove_filter( 'list_pages', 'my_list_pages_custom_field', 10 );
                $product = wc_get_product( $devlloeventsselectticket );

                if ($product){
                echo '<div>Ticket Price: ' . wc_price($product->get_price()) . '</div>';
                }
}
          

// Save checkbox
add_action('save_post', 'save_devllo_event_select_ticket_checkbox'); 

function save_devllo_event_select_ticket_checkbox($product_id){

    // Add nonce for security and authentication.
    if ( ! isset( $_POST['devllo_event_select_ticket_inner_custom_box_nonce'] ) ) {
        return $product_id;
    }
    
    $nonce = $_POST['devllo_event_select_ticket_inner_custom_box_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'devllo_event_select_ticket_inner_custom_box' ) ) {
        return $product_id;
    }
    
    /*
    * If this is an autosave, our form has not been submitted,
    * so we don't want to do anything.
    */
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $product_id;
    }

    //Sanitize fields
    if (isset($_POST['devllo_events_select_ticket_field'])){
        $devllo_event_woocommerce_ticket = sanitize_key($_POST['devllo_events_select_ticket_field']);
    }
    
    //Update and save fields

    if (isset($_POST['devllo_events_select_ticket_field'])){
        update_post_meta( $product_id, 'devllo_events_select_ticket_key', $devllo_event_woocommerce_ticket );
    }else{
        delete_post_meta($product_id, 'devllo_events_select_ticket_key');
    }
}