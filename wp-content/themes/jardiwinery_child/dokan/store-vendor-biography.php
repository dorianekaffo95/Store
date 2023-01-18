<?php
/**
 * The Template for displaying vendor biography.
 *
 * @package dokan
 */

$store_user = get_userdata( get_query_var( 'author' ) );
$store_info   = dokan_get_store_info( $store_user->ID );
$map_location = $store_user->get_location();
$layout       = get_theme_mod( 'store_layout', 'left' );

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
            <?php do_action( 'dokan_vendor_biography_tab_before', $store_user, $store_info ); ?>

            <?php
                // Use shortcodes in form like Landing Page Template.
                echo do_shortcode( '[ic_add_posts tag="'.$store_user->user_nicename.'"]' );
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
