<?php
/* ThemeREX Updater support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_trx_updater_theme_setup')) {
    add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_trx_updater_theme_setup' );
    function jardiwinery_trx_updater_theme_setup() {

        if (is_admin()) {
           
            add_filter( 'jardiwinery_filter_required_plugins',				'jardiwinery_trx_updater_required_plugins' );
        }
    }
}

// Check if RevSlider installed and activated
if ( !function_exists( 'jardiwinery_exists_trx_updater' ) ) {
    function jardiwinery_exists_trx_updater() {
        return defined( 'TRX_UPDATER_VERSION' );
    }
}


// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_trx_updater_required_plugins' ) ) {
    function jardiwinery_trx_updater_required_plugins($list=array()) {
        if (in_array('trx_updater', jardiwinery_storage_get('required_plugins'))) {
            $path = jardiwinery_get_file_dir('plugins/install/trx_updater.zip');
            if (file_exists($path)) {
                $list[] = array(
                    'name' 		=> esc_html__('ThemeREX Updater', 'jardiwinery'),
                    'slug' 		=> 'trx_updater',
                    'version'  => '1.4.1',
                    'source'	=> $path,
                    'required' 	=> false
                );
            }
        }
        return $list;
    }
}