<?php
/**
 * The Template for displaying vendor biography.
 *
 * @package dokan
 */

$store_user     = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info     = dokan_get_store_info( $store_user->ID );
$map_location   = $store_user->get_location();
$layout         = get_theme_mod( 'store_layout', 'left' );
$store_address  = dokan_get_seller_short_address( $store_user->get_id(), false );

get_header( 'shop' );
?>

<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="dokan-store-wrap layout-<?php echo esc_attr( $layout ); ?>">

    <?php if ( 'left' === $layout ) { ?>
        <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
    <?php } ?>

<div id="dokan-primary" class="dokan-single-store">
    <div id="dokan-content" class="store-review-wrap woocommerce" role="main">

        <?php dokan_get_template_part( 'store-header' ); ?>

        <div id="vendor-biography">
            <div id="comments">

            <p style="color:#f00;"><i class="fa fa-map-marker"></i> <?php echo wp_kses_post( $store_address ); ?></p>
            <?php //do_action( 'dokan_vendor_biography_tab_before', $store_user, $store_info ); ?>

            <?php do_action( 'dokan_sidebar_store_before', $store_user->data, $store_info );
                $args = [
                    'before_widget' => '<aside class="widget dokan-store-widget %s">',
                    'after_widget'  => '</aside>',
                    'before_title'  => '<h4 style="margin:1rem 0;" class="widget-title">',
                    'after_title'   => '</h4>',
                ];

                if ( ! empty( $map_location ) ) {
                    the_widget( dokan()->widgets->store_location, [ 'title' => __( 'Google map location', 'vmc' ) ], $args );
                }
                //the_widget( dokan()->widgets->store_contact_form, [ 'title' => __( 'Contacter nous', 'vmc' ) ], $args );
		?>
                <h4 class="widget-title" style="margin:1rem 0;"><?php echo __( 'Contact us', 'vmc' ); ?></h4>
                <?php 

		echo do_shortcode( '[contact-form-7 id="1211" title="contact-vendor" vendor-name=""]' );

                do_action( 'dokan_sidebar_store_after', $store_user->data, $store_info );

                do_action( 'dokan_vendor_biography_tab_after', $store_user, $store_info ); 
            ?>

            </div>
        </div>

    </div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

    <?php if ( 'right' === $layout ) { ?>
        <?php dokan_get_template_part( 'store', 'sidebar', array( 'store_user' => $store_user, 'store_info' => $store_info, 'map_location' => $map_location ) ); ?>
    <?php } ?>

</div><!-- .dokan-store-wrap -->

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer(); ?>
