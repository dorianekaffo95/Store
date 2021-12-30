<?php
// Get theme variable
if (!function_exists('trx_utils_storage_get')) {
    function trx_utils_storage_get($var_name, $default='') {
        global $JARDIWINERY_STORAGE;
        return isset($JARDIWINERY_STORAGE[$var_name]) ? $JARDIWINERY_STORAGE[$var_name] : $default;
    }
}

// Get GET, POST value
if (!function_exists('trx_utils_get_value_gp')) {
    function trx_utils_get_value_gp($name, $defa='') {
        if (isset($_GET[$name]))        $rez = $_GET[$name];
        else if (isset($_POST[$name]))  $rez = $_POST[$name];
        else                            $rez = $defa;
        return trx_utils_stripslashes($rez);
    }
}

// Strip slashes if Magic Quotes is on
if (!function_exists('trx_utils_stripslashes')) {
    function trx_utils_stripslashes($val) {
        static $magic = 0;
        if ($magic === 0) {
            $magic = version_compare(phpversion(), '5.4', '>=')
                || (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()==1)
                || (function_exists('get_magic_quotes_runtime') && get_magic_quotes_runtime()==1)
                || strtolower(ini_get('magic_quotes_sybase'))=='on';
        }
        if (is_array($val)) {
            foreach($val as $k=>$v)
                $val[$k] = trx_utils_stripslashes($v);
        } else
            $val = $magic ? stripslashes(trim($val)) : trim($val);
        return $val;
    }
}

//Return Post Views Count
if (!function_exists('trx_utils_get_post_views')) {
    add_filter('trx_utils_filter_get_post_views', 'trx_utils_get_post_views', 10, 2);
    function trx_utils_get_post_views($default=0, $id=0){
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = trx_utils_storage_get('options_prefix').'_post_views_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, '0');
            $count = 0;
        }
        return $count;
    }
}

//Set Post Views Count
if (!function_exists('trx_utils_set_post_views')) {
    add_action('trx_utils_filter_set_post_views', 'trx_utils_set_post_views', 10, 2);
    function trx_utils_set_post_views($id=0, $counter=-1) {
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = trx_utils_storage_get('options_prefix').'_post_views_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, 1);
        } else {
            $count = $counter >= 0 ? $counter : $count+1;
            update_post_meta($id, $count_key, $count);
        }
    }
}

//Return Post Likes Count
if (!function_exists('trx_utils_get_post_likes')) {
    add_filter('trx_utils_filter_get_post_likes', 'trx_utils_get_post_likes', 10, 2);
    function trx_utils_get_post_likes($default=0, $id=0){
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = trx_utils_storage_get('options_prefix').'_post_likes_count';
        $count = get_post_meta($id, $count_key, true);
        if ($count===''){
            delete_post_meta($id, $count_key);
            add_post_meta($id, $count_key, '0');
            $count = 0;
        }
        return $count;
    }
}

//Set Post Likes Count
if (!function_exists('trx_utils_set_post_likes')) {
    add_action('trx_utils_filter_set_post_likes', 'trx_utils_set_post_likes', 10, 2);
    function trx_utils_set_post_likes($id=0, $count=0) {
        global $wp_query;
        if (!$id) $id = $wp_query->current_post>=0 ? get_the_ID() : $wp_query->post->ID;
        $count_key = trx_utils_storage_get('options_prefix').'_post_likes_count';
        update_post_meta($id, $count_key, $count);
    }
}

// AJAX: Set post likes/views count
//Handler of add_action('wp_ajax_post_counter', 			'trx_utils_callback_post_counter');
//Handler of add_action('wp_ajax_nopriv_post_counter',	'trx_utils_callback_post_counter');
if ( !function_exists( 'trx_utils_callback_post_counter' ) ) {
    function trx_utils_callback_post_counter() {

        if ( !wp_verify_nonce( trx_utils_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
            wp_die();

        $response = array('error'=>'');

        $id = (int) trx_utils_get_value_gpc('post_id');
        if (isset($_REQUEST['likes'])) {
            $counter = max(0, (int) $_REQUEST['likes']);
            trx_utils_set_post_likes($id, $counter);
        } else if (isset($_REQUEST['views'])) {
            $counter = max(0, (int) $_REQUEST['views']);
            trx_utils_set_post_views($id, $counter);
        }
        echo json_encode($response);
        wp_die();
    }
}