<?php
// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function say_hello_world() {
    if($_GET['s']  === "") {
    	return "empty";
    }

    if($_GET['post_type'] == 'product') {
        $args = array( 
            'post_type' => sanitize_text_field( wp_unslash( $_GET['post_type'] ) ), 
            's' => sanitize_text_field( wp_unslash( $_GET['s'] ) ), 
            'posts_per_page' => 24
        );
        return new WP_Query( $args );
    } elseif($_GET['post_type'] == 'vendor') {
	$_GET['dokan_seller_search'] = $_GET['s'];
	echo do_shortcode( '[dokan-stores]' );
    } elseif($_GET['post_type'] == 'prix') {
	$values = explode("-", sanitize_text_field($_GET['s']));
        $query = array(
	    'post_type' => 'product',
	    'limit' => 24,
            'posts_per_page' => 24,
            'meta_query' => array(
            	array(
                	'key' => '_price',
                	'value' => array(intval($values[0]), intval($values[1])),
                	'compare' => 'BETWEEN',
                	'type' => 'NUMERIC'
                )
            )
        );
	return new WP_Query($query);
    } else {
	return "empty";
    }
}

function get_post_type_search_query() {
  return isset($_REQUEST['post_type']) ? sanitize_text_field( wp_unslash( $_REQUEST['post_type'] ) ) : '';
}
