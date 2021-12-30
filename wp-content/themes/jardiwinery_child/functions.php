<?php
/**
 * Child-Theme functions and definitions
 */

function my_theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

// Conditional Nav Menu

function wpc_wp_nav_menu_args( $args ) {
  if($args['theme_location'] != 'menu_main') {
    return $args;
  }

  if( is_user_logged_in() ) { 
    $args['menu'] = 'logged-in';
  } else { 
    $args['menu'] = 'logged-out';
  }
  return $args;
}
add_filter( 'wp_nav_menu_args', 'wpc_wp_nav_menu_args' );

#-- Add New Tab on Store Page --#
function add_new_store_tab($tabs, $store_id){
  #-- Renamed the default products tab --#
  $tabs['products']['title'] = __( 'Nos Produits', 'dokan' );
  
  $tabs['visitenous'] = [
      'title' => __( 'Visite nous', 'dokan' ),
      'url'   => 'https://experience.primusevent.com/'
  ];

  #-- New Tab --#
  $tabs['contact'] = [
      'title' => __( 'Qui sommes-nous', 'dokan' ),
      'url'   => dokan_get_store_url( $store_id ) . 'biography'
  ];

  unset($tabs['reviews']);
  $tabs['vendor'] = [
      'title' => __( 'Nous contacter', 'dokan' ),
      'url'   => dokan_get_store_url( $store_id ) . 'reviews'
  ];
  
  return $tabs;
}
add_filter( 'dokan_store_tabs', 'add_new_store_tab', 11, 2 );


#-- Load Template --#
function load_vendor_template( $template ) {
  if ( get_query_var( 'biography' ) ) {
        return dokan_locate_template( 'store-vendor-biography.php', '', __DIR__.'/dokan/', true );
  } 
  return $template;
}
add_filter( 'template_include', 'load_vendor_template', 100 );

#-- Load Template --#
function load_visitenous_template( $template ) {
  if ( get_query_var( 'store_review' ) ) {
      return dokan_locate_template( 'store-vendor-contact.php', '', __DIR__.'/dokan/', true );
  } 
  return $template;
}
add_filter( 'template_include', 'load_visitenous_template', 100 );
