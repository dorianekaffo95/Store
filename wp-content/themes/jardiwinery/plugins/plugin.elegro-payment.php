<?php
/* Elegro Crypto Payment support functions
------------------------------------------------------------------------------- */

// Check if plugin installed and activated
if ( !function_exists( 'jardiwinery_exists_elegro_payment' ) ) {
    function jardiwinery_exists_elegro_payment() {
        return class_exists( 'WC_Elegro_Payment' );
    }
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('jardiwinery_elegro_payment_theme_setup')) {
    add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_elegro_payment_theme_setup', 1 );
    function jardiwinery_elegro_payment_theme_setup() {
        if (jardiwinery_exists_elegro_payment()) {
            add_action('jardiwinery_action_add_styles',  'jardiwinery_elegro_payment_frontend_scripts' );
        }
        if (is_admin()) {
            add_filter( 'jardiwinery_filter_required_plugins',       'jardiwinery_elegro_payment_required_plugins' );
        }
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_elegro_payment_required_plugins' ) ) {
    function jardiwinery_elegro_payment_required_plugins($list=array()) {
        if (in_array('elegro-payment', (array)jardiwinery_storage_get('required_plugins'))) {
            $list[] = array(
                'name'      => esc_html__('Elegro Crypto Payment', 'jardiwinery'),
                'slug'      => 'elegro-payment',
                'required'  => false
            );
        }
        return $list;
    }
}


// Enqueue Elegro Payment custom styles
if ( !function_exists( 'jardiwinery_elegro_payment_frontend_scripts' ) ) {
    function jardiwinery_elegro_payment_frontend_scripts() {
        if (file_exists(jardiwinery_get_file_dir('css/plugin.elegro-payment.css')))
            wp_enqueue_style( 'jardiwinery-plugin-elegro-payment-style',  jardiwinery_get_file_url('css/plugin.elegro-payment.css'), array(), null );
    }
}
?>