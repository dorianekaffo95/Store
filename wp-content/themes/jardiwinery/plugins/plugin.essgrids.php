<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_essgrids_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_essgrids_theme_setup', 1 );
	function jardiwinery_essgrids_theme_setup() {
		// Register shortcode in the shortcodes list

		if (is_admin()) {
			add_filter( 'jardiwinery_filter_importer_required_plugins',	'jardiwinery_essgrids_importer_required_plugins', 10, 2 );
			add_filter( 'jardiwinery_filter_required_plugins',				'jardiwinery_essgrids_required_plugins' );
		}
	}
}


// Check if Ess. Grid installed and activated
if ( !function_exists( 'jardiwinery_exists_essgrids' ) ) {
	function jardiwinery_exists_essgrids() {
		return defined('EG_PLUGIN_PATH');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_essgrids_required_plugins' ) ) {
	
	function jardiwinery_essgrids_required_plugins($list=array()) {
		if (in_array('essgrids', jardiwinery_storage_get('required_plugins'))) {
			$path = jardiwinery_get_file_dir('plugins/install/essential-grid.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Essential Grid', 'jardiwinery'),
					'slug' 		=> 'essential-grid',
                    'version'	=> '3.0.7',
                    'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'jardiwinery_essgrids_importer_required_plugins' ) ) {
	
	function jardiwinery_essgrids_importer_required_plugins($not_installed='', $list='') {
		if (jardiwinery_strpos($list, 'essgrids')!==false && !jardiwinery_exists_essgrids() )
			$not_installed .= '<br>'.esc_html__('Essential Grids', 'jardiwinery');
		return $not_installed;
	}
}
?>