<?php
/* Product Delivery Date for WooCommerce - Lite support functions
------------------------------------------------------------------------------- */

// Check if plugin installed and activated
if ( !function_exists( 'jardiwinery_exists_product_delivery_date_for_woocommerce_lite_payment' ) ) {
    function jardiwinery_exists_product_delivery_date_for_woocommerce_lite_payment() {
        return class_exists( 'Prdd_Lite_Woocommerce' );
    }
}

// Theme init
if (!function_exists('jardiwinery_product_delivery_date_for_woocommerce_lite_theme_setup')) {
    add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_product_delivery_date_for_woocommerce_lite_theme_setup', 1 );
    function jardiwinery_product_delivery_date_for_woocommerce_lite_theme_setup() {
        if (jardiwinery_exists_product_delivery_date_for_woocommerce_lite_payment()) {
            add_action('jardiwinery_action_add_styles',  'jardiwinery_product_delivery_date_for_woocommerce_lite_frontend_scripts' );
        }
        if (is_admin()) {
            add_filter( 'jardiwinery_filter_required_plugins', 'jardiwinery_product_delivery_date_for_woocommerce_lite_required_plugins' );
        }
    }
}


// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_product_delivery_date_for_woocommerce_lite_required_plugins' ) ) {
    function jardiwinery_product_delivery_date_for_woocommerce_lite_required_plugins($list=array()) {
        if (in_array('product-delivery-date-for-woocommerce-lite', (array)jardiwinery_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Product Delivery Date for WooCommerce - Lite', 'jardiwinery'),
                'slug'         => 'product-delivery-date-for-woocommerce-lite',
                'required'     => false
            );
        return $list;
    }
}

// Enqueue Elegro Payment custom styles
if ( !function_exists( 'jardiwinery_product_delivery_date_for_woocommerce_lite_frontend_scripts' ) ) {
    function jardiwinery_product_delivery_date_for_woocommerce_lite_frontend_scripts() {
        if (file_exists(jardiwinery_get_file_dir('css/plugin.product-delivery-date-for-woocommerce-lite.css')))
            wp_enqueue_style( 'jardiwinery-plugin-product-delivery-date-for-woocommerce-lite-style',  jardiwinery_get_file_url('css/plugin.product-delivery-date-for-woocommerce-lite.css'), array(), null );
    }
}


