<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_revslider_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_revslider_theme_setup', 1 );
	function jardiwinery_revslider_theme_setup() {
		if (jardiwinery_exists_revslider()) {
			add_filter( 'jardiwinery_filter_list_sliders',					'jardiwinery_revslider_list_sliders' );
			add_filter( 'jardiwinery_filter_shortcodes_params',			'jardiwinery_revslider_shortcodes_params' );
			add_filter( 'jardiwinery_filter_theme_options_params',			'jardiwinery_revslider_theme_options_params' );
		}
		if (is_admin()) {
			add_filter( 'jardiwinery_filter_importer_required_plugins',	'jardiwinery_revslider_importer_required_plugins', 10, 2 );
			add_filter( 'jardiwinery_filter_required_plugins',				'jardiwinery_revslider_required_plugins' );
		}
	}
}

if ( !function_exists( 'jardiwinery_revslider_settings_theme_setup2' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_revslider_settings_theme_setup2', 3 );
	function jardiwinery_revslider_settings_theme_setup2() {
		if (jardiwinery_exists_revslider()) {

			// Add Revslider specific options in the Theme Options
			jardiwinery_storage_set_array_after('options', 'slider_engine', "slider_alias", array(
				"title" => esc_html__('Revolution Slider: Select slider',  'jardiwinery'),
				"desc" => wp_kses_data( __("Select slider to show (if engine=revo in the field above)", 'jardiwinery') ),
				"override" => "category,services_group,page",
				"dependency" => array(
					'show_slider' => array('yes'),
					'slider_engine' => array('revo')
				),
				"std" => "",
				"options" => jardiwinery_get_options_param('list_revo_sliders'),
				"type" => "select"
				)
			);

		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'jardiwinery_exists_revslider' ) ) {
	function jardiwinery_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_revslider_required_plugins' ) ) {
	
	function jardiwinery_revslider_required_plugins($list=array()) {
		if (in_array('revslider', jardiwinery_storage_get('required_plugins'))) {
			$path = jardiwinery_get_file_dir('plugins/install/revslider.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Revolution Slider', 'jardiwinery'),
					'slug' 		=> 'revslider',
                    'version'	=> '6.2.23',
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

// Check RevSlider in the required plugins
if ( !function_exists( 'jardiwinery_revslider_importer_required_plugins' ) ) {
	
	function jardiwinery_revslider_importer_required_plugins($not_installed='', $list='') {

		if (jardiwinery_strpos($list, 'revslider')!==false && !jardiwinery_exists_revslider() )
			$not_installed .= '<br>' . esc_html__('Revolution Slider', 'jardiwinery');
		return $not_installed;
	}
}



// Lists
//------------------------------------------------------------------------

// Add RevSlider in the sliders list, prepended inherit (if need)
if ( !function_exists( 'jardiwinery_revslider_list_sliders' ) ) {
	
	function jardiwinery_revslider_list_sliders($list=array()) {
		$list["revo"] = esc_html__("Layer slider (Revolution)", 'jardiwinery');
		return $list;
	}
}

// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'jardiwinery_get_list_revo_sliders' ) ) {
	function jardiwinery_get_list_revo_sliders($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_revo_sliders'))=='') {
			$list = array();
			if (jardiwinery_exists_revslider()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$list = apply_filters('jardiwinery_filter_list_revo_sliders', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_revo_sliders', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Add RevSlider in the shortcodes params
if ( !function_exists( 'jardiwinery_revslider_shortcodes_params' ) ) {
	
	function jardiwinery_revslider_shortcodes_params($list=array()) {
		$list["revo_sliders"] = jardiwinery_get_list_revo_sliders();
		return $list;
	}
}

// Add RevSlider in the Theme Options params
if ( !function_exists( 'jardiwinery_revslider_theme_options_params' ) ) {
	
	function jardiwinery_revslider_theme_options_params($list=array()) {
		$list["list_revo_sliders"] = array('$jardiwinery_get_list_revo_sliders' => '');
		return $list;
	}
}
?>