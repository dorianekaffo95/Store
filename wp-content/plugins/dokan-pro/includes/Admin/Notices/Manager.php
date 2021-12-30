<?php

namespace WeDevs\DokanPro\Admin\Notices;

/**
 * Admin notices handler class
 *
 * @since 3.4.3
 */
class Manager {
    /**
     * Class constructor
     */
    public function __construct() {
        $this->init_classes();
        $this->init_hooks();
    }

    /**
     * Register all notices classes to chainable container
     *
     * @since 3.4.3
     *
     * @return void
     */
    private function init_classes() {
        new DokanLiteMissing();
        new WhatsNew();
    }

    /**
     * Load Hooks
     *
     * @since 3.4.3
     *
     * @return void
     */
    private function init_hooks() {
        // limited time table rate shipping remove notice, will be removed in near future
        add_action( 'dokan_loaded', [ $this, 'display_table_rate_shipping_notice' ], 20 );
    }

    /**
     * Needed extra wrapper because Manager class was loaded on Dokan pro constractor and module object was loading later on
     *
     * @since 3.4.3
     *
     * @return void
     */
    public function display_table_rate_shipping_notice() {
        if ( count( dokan_pro()->module->get_available_modules()  ) <= 4 && current_user_can( 'manage_woocommerce' ) ) {
            add_filter( 'dokan_admin_notices', [ $this, 'remove_table_rate_shipping_module_notice' ] );
            add_action( 'wp_ajax_dismiss_table_rate_shipping_module', [ $this, 'ajax_dismiss_table_rate_shipping_module' ] );
        }
    }

    /**
     * Display dismiss Table Rate Shipping module notice
     *
     * @since 3.4.3
     *
     * @param array $notices
     *
     * @return array
     */
    public function remove_table_rate_shipping_module_notice( $notices ) {
        if ( 'yes' === get_option( 'dismiss_table_rate_shipping_module', 'no' ) ) {
            return $notices;
        }

        $notices[] = [
            'type'        => 'alert',
            'title'       => __( 'Table Rate Shipping will be removed from Dokan Starter package in the next release.', 'dokan' ),
            /* translators: %s permalink settings url */
            'description' => __( 'Due to a technical error on our end, we deployed the Table rate shipping module to the Starter package. The module will be disabled to Starter plan users in a few weeks. We apologize for the mistake and the inconvenience caused. If you want to continue availing the functionalities of the module, please upgrade to the Professional plan. You may use this coupon code to avail a 30% discount: <strong>UpgradeDokan30</strong>', 'dokan' ),
            'priority'    => 1,
            'show_close_button' => true,
            'ajax_data'   => [
                'action' => 'dismiss_table_rate_shipping_module',
                'nonce'  => wp_create_nonce( 'dismiss_table_rate_shipping_nonce' ),
            ],
            'actions'     => [
                [
                    'type'   => 'primary',
                    'text'   => __( 'Upgrade Now', 'dokan' ),
                    'action' => 'https://wedevs.com/dokan/pricing',
                    'target' => '_blank',
                ],
            ],
        ];

        return $notices;
    }

    /**
     * Dismiss Table Rate Shipping module ajax action.
     *
     * @since 3.4.3
     *
     * @return void
     */
    public function ajax_dismiss_table_rate_shipping_module() {
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'dismiss_table_rate_shipping_nonce' ) ) {
            wp_send_json_error( __( 'Invalid nonce', 'dokan' ) );
        }

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error( __( 'You have no permission to do that', 'dokan-lite' ) );
        }

        update_option( 'dismiss_table_rate_shipping_module', 'yes' );

        wp_send_json_success();
    }
}
