<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_contact_form_7_theme_setup')) {
    add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_contact_form_7_theme_setup', 1 );
    function jardiwinery_contact_form_7_theme_setup() {
        if (is_admin()) {
            add_filter( 'jardiwinery_filter_required_plugins', 'jardiwinery_contact_form_7_required_plugins' );
        }
    }
}

// Check if Contact Form 7 installed and activated
if ( !function_exists( 'jardiwinery_exists_contact_form_7' ) ) {
    function jardiwinery_exists_contact_form_7() {
        return defined( 'Contact Form 7' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_contact_form_7_required_plugins' ) ) {
    
    function jardiwinery_contact_form_7_required_plugins($list=array()) {
        if (in_array('contact_form_7', (array)jardiwinery_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Contact Form 7', 'jardiwinery'),
                'slug'         => 'contact-form-7',
                'required'     => false
            );
        return $list;
    }
}
