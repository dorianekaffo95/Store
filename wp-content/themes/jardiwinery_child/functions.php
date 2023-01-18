<?php
/**
 * Child-Theme functions and definitions
 */

require_once 'widgets/search/class-vmc-search.php';

require_once 'multisearch_query.php';

function my_theme_enqueue_styles() {
wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

/** Conditional Nav Menu **/

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
  $tabs['products']['title'] = __( 'Our Products', 'vmc' );
  
  $tabs['visitenous'] = [
      'title' => __( 'Visit us', 'vmc' ),
      'url'   => 'https://experience.primusevent.com/'
  ];

  #-- New Tab --#
  $tabs['contact'] = [
      'title' => __( 'Who we are', 'vmc' ),
      'url'   => dokan_get_store_url( $store_id ) . 'biography'
  ];

  unset($tabs['reviews']);
  $tabs['vendor'] = [
      'title' => __( 'Contact us', 'vmc' ),
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


function initialize_search_widget_register() {
    register_widget(Vmc_Search_Widget::class );
}

// Widget initialization hook
add_action( 'widgets_init', 'initialize_search_widget_register' );


// internationalisation
add_action('after_setup_theme', function() {
    load_theme_textdomain('vmc', get_template_directory() . '/languages');
});


add_filter( 'query_vars', 'multisearch_query_vars' );
add_filter( 'template_include', 'multisearch_template', 50 );
add_action( 'generate_rewrite_rules', 'multisearch_resources_cpt_generating_rule' );


function multisearch_resources_cpt_generating_rule( $wp_rewrite ) {
    $rules = [
        'multisearch/?$' => 'index.php?multisearch=true&activities=true'
    ];
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;

    return $wp_rewrite;
}

function multisearch_query_vars( $query_vars ) {
    array_push( $query_vars, 'multisearch' );
    return $query_vars;
}

function multisearch_template( $template ) {
    $qv = get_query_var('multisearch', null);
    if ( $qv !== null ) {
        // use "get_stylesheet_directory()" or "get_template_directory()"
        // if template file is in theme directory 
        $template =  dirname(__FILE__) . '/multisearch_template.php';
    }
    return $template;
}

add_filter('multisearch_before_content', function() {
  the_widget('Vmc_Search_Widget', [],
    [
        'before_widget' => '<section class="widget__container multisearch">',
        'after_widget'  => '</section>',
        'before_title'  => '<h5 class="widget__title">',
        'after_title'   => '</h5>',
    ]
  );
});


add_filter('dokan_store_profile_frame_after', function() {
  dokan_store_category_widget();
});


function jardiwinery_child_theme_scripts_function() {
  wp_enqueue_script( 'theme.child', jardiwinery_get_file_url('/js/theme.child.customier.js'));
  wp_enqueue_script('jquery-ui-slider', jardiwinery_get_file_url('/js/slider.min.js'));
}
add_action('wp_enqueue_scripts','jardiwinery_child_theme_scripts_function');


add_filter( 'shortcode_atts_wpcf7', 'vendor_shortcode_atts_wpcf7_filter', 10, 3 );
 
function vendor_shortcode_atts_wpcf7_filter( $out, $pairs, $atts ) {
  if($atts["id"] != "1211") {
    return $out;
  }

  $store_user = dokan()->vendor->get( get_query_var( 'author' ) );
  $store_info     = dokan_get_store_info( $store_user->ID );
  $my_attr = 'vendor-name';
 
  if ( isset( $atts[$my_attr] ) ) {
    $out[$my_attr] = $store_user->get_shop_name();
  }
 
  return $out;
}
