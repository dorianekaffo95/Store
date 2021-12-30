<?php
/**
 * JardiWinery Framework: Registered Users
 *
 * @package	jardiwinery
 * @since	jardiwinery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('jardiwinery_users_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_users_theme_setup' );
	function jardiwinery_users_theme_setup() {

		if ( is_admin() ) {
			// Add extra fields in the user profile
            if (function_exists('jardiwinery_add_fields_in_user_info')){
                add_action( 'show_user_profile',		'jardiwinery_add_fields_in_user_info' );
                add_action( 'edit_user_profile',		'jardiwinery_add_fields_in_user_info' );
            }			
	
			// Save / update additional fields from profile
            if ( function_exists('jardiwinery_save_fields_in_user_info')){
                add_action( 'personal_options_update',	'jardiwinery_save_fields_in_user_info' );
                add_action( 'edit_user_profile_update',	'jardiwinery_save_fields_in_user_info' );
            }

        } else {

            // Social Login support
            add_filter( 'trx_utils_filter_social_login', 'jardiwinery_social_login');
        }

	}
}

// Return Social Login layout (if present)
if (!function_exists('jardiwinery_social_login')) {
    function jardiwinery_social_login($sc) {
        return jardiwinery_get_theme_option('social_login');
    }
}

// Return (and show) user profiles links
if (!function_exists('jardiwinery_show_user_socials')) {
	function jardiwinery_show_user_socials($args) {
		$args = array_merge(array(
			'author_id' => 0,										// author's ID
			'allowed' => array(),									// list of allowed social
			'size' => 'small',										// icons size: tiny|small|big
			'shape' => 'square',									// icons shape: square|round
			'style' => jardiwinery_get_theme_setting('socials_type')=='images' ? 'bg' : 'icons',	// style for show icons: icons|images|bg
			'echo' => true											// if true - show on page, else - only return as string
			), is_array($args) ? $args 
				: array('author_id' => $args));						// If send one number parameter - use it as author's ID
		$output = '';
		$upload_info = wp_upload_dir();
		$upload_url = $upload_info['baseurl'];
		$social_list = jardiwinery_get_theme_option('social_icons');
		$list = array();
		if (is_array($social_list) && count($social_list) > 0) {
			foreach ($social_list as $soc) {
				if ($args['style'] == 'icons') {
					$parts = explode('-', $soc['icon'], 2);
					$sn = isset($parts[1]) ? $parts[1] : $soc['icon'];
				} else {
					$sn = basename($soc['icon']);
					$sn = jardiwinery_substr($sn, 0, jardiwinery_strrpos($sn, '.'));
					if (($pos=jardiwinery_strrpos($sn, '_'))!==false)
						$sn = jardiwinery_substr($sn, 0, $pos);
				}
				if (count($args['allowed'])==0 || in_array($sn, $args['allowed'])) {
					$link = get_the_author_meta('user_' . ($sn), $args['author_id']);
					if ($link) {
						$icon = $args['style']=='icons' || jardiwinery_strpos($soc['icon'], $upload_url)!==false ? $soc['icon'] : jardiwinery_get_socials_url(basename($soc['icon']));
						$list[] = array(
							'icon'	=> $icon,
							'url'	=> $link
						);
					}
				}
			}
		}
		if (count($list) > 0) {
			$output = '<div class="sc_socials sc_socials_size_'.esc_attr($args['size']).' sc_socials_shape_'.esc_attr($args['shape']).' sc_socials_type_' . esc_attr($args['style']) . '">' 
							. trim(jardiwinery_prepare_socials($list, $args['style'])) 
						. '</div>';
			if ($args['echo']) jardiwinery_show_layout($output);
		}
		return $output;
	}
}
?>