<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_booked_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_booked_theme_setup', 1 );
	function jardiwinery_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (jardiwinery_exists_booked()) {
			add_action('jardiwinery_action_add_styles', 					'jardiwinery_booked_frontend_scripts');

		}
		if (is_admin()) {
			add_filter( 'jardiwinery_filter_importer_required_plugins',	'jardiwinery_booked_importer_required_plugins', 10, 2);
			add_filter( 'jardiwinery_filter_required_plugins',				'jardiwinery_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'jardiwinery_exists_booked' ) ) {
	function jardiwinery_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_booked_required_plugins' ) ) {
	
	function jardiwinery_booked_required_plugins($list=array()) {
		if (in_array('booked', jardiwinery_storage_get('required_plugins'))) {
			$path = jardiwinery_get_file_dir('plugins/install/booked.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booked', 'jardiwinery'),
					'slug' 		=> 'booked',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'jardiwinery_booked_frontend_scripts' ) ) {
	
	function jardiwinery_booked_frontend_scripts() {
		if (file_exists(jardiwinery_get_file_dir('css/plugin.booked.css')))
			wp_enqueue_style( 'jardiwinery-plugin-booked-style',  jardiwinery_get_file_url('css/plugin.booked.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'jardiwinery_booked_importer_required_plugins' ) ) {
	
	function jardiwinery_booked_importer_required_plugins($not_installed='', $list='') {

		if (jardiwinery_strpos($list, 'booked')!==false && !jardiwinery_exists_booked() )
			$not_installed .= '<br>' . esc_html__('Booked Appointments', 'jardiwinery');
		return $not_installed;
	}
}




// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'jardiwinery_get_list_booked_calendars' ) ) {
	function jardiwinery_get_list_booked_calendars($prepend_inherit=false) {
		return jardiwinery_exists_booked() ? jardiwinery_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



?>