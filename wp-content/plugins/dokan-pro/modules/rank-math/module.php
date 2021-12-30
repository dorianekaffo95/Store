<?php

namespace WeDevs\DokanPro\Modules\RankMath;

defined( 'ABSPATH' ) || exit;

/**
 * Class for Rank math SEO integration module
 *
 * @since 3.4.0
 */
class Module {

    /**
     * Class constructor
     *
     * @since 3.4.0
     */
    public function __construct() {
        $this->constants();

        // Verify if `Rank Math SEO` plugin is activated
        if ( ! class_exists( 'RankMath' ) ) {
            if ( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }

            add_filter( 'dokan_admin_notices', [ $this, 'rank_math_activation_notice' ] );
            add_action( 'wp_ajax_dokan_install_rank_math_seo', array( $this, 'install_rank_math_seo' ) );
            add_action( 'wp_ajax_dokan_activate_rank_math_seo', array( $this, 'activate_rank_math_seo' ) );

            //return from here
            return;
        }

        // Check if current user has the permission to edit product
        if ( ! current_user_can( 'dokan_edit_product' ) ) {
            return;
        }

        // Initialize the module
        add_action( 'init', array( $this, 'hooks' ) );
    }

    /**
     * Rank Math SEO plugin activation notice
     *
     * @since 3.4.0
     *
     * @param array $notices
     *
     * @return array
     * */
    public function rank_math_activation_notice( $notices ) {
        $rank_math_plugin_file  = 'seo-by-rank-math/rank-math.php';
        $is_rank_math_installed = $this->is_rank_math_installed();

        if ( $is_rank_math_installed ) {
            $notices[] = [
                'type'        => 'success',
                'title'       => __( 'Dokan Rank Math SEO module is almost ready!', 'dokan' ),
                'description' => sprintf( __( 'You just need to activate the %s plugin to make it functional.', 'dokan' ), '<strong>Rank Math SEO</strong>' ),
                'priority'    => 10,
                'actions'     => [
                    [
                        'type'           => 'primary',
                        'text'           => __( 'Activate this plugin', 'dokan' ),
                        'loading_text'   => __( 'Activating...', 'dokan' ),
                        'completed_text' => __( 'Activated', 'dokan' ),
                        'reload'         => true,
                        'ajax_data'      => [
                            'action'   => 'dokan_activate_rank_math_seo',
                            '_wpnonce' => wp_create_nonce( 'dokan-rank-math-activate-nonce' ),
                        ],
                    ],
                ],
            ];
        } else {
            $notices[] = [
                'type'        => 'alert',
                'title'       => __( 'Dokan Rank Math SEO module is almost ready!', 'dokan' ),
                'description' => sprintf( __( 'You just need to install the %s plugin to make it functional.', 'dokan' ), '<a target="_blank" href="https://wordpress.org/plugins/seo-by-rank-math/">Rank Math SEO</a>' ),
                'priority'    => 10,
                'actions'     => [
                    [
                        'type'           => 'secondary',
                        'text'           => __( 'Install Now', 'dokan' ),
                        'loading_text'   => __( 'Installing...', 'dokan' ),
                        'completed_text' => __( 'Installed', 'dokan' ),
                        'reload'         => true,
                        'ajax_data'      => [
                            'action'   => 'dokan_install_rank_math_seo',
                            '_wpnonce' => wp_create_nonce( 'dokan-rank-math-installer-nonce' ),
                        ],
                    ],
                ],
            ];
        }

        return $notices;
    }

    /**
     * Activate Rank Math SEO plugin
     *
     * @since 3.4.3
     *
     * @return void
     * */
    public function activate_rank_math_seo() {
        if (
            ! isset( $_REQUEST['_wpnonce'] ) ||
            ! wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'dokan-rank-math-activate-nonce' ) // phpcs:ignore
        ) {
            wp_send_json_error( __( 'Error: Nonce verification failed', 'dokan' ) );
        }

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error( __( 'You have no permission to do that', 'dokan-lite' ) );
        }

        activate_plugin( 'seo-by-rank-math/rank-math.php' );

        wp_send_json_success();
    }

    /**
     * Installs Rank Math SEO plugin
     *
     * @since 3.4.0
     *
     * @return void
     * */
    public function install_rank_math_seo() {
        if (
            ! isset( $_REQUEST['_wpnonce'] ) ||
            ! wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'dokan-rank-math-installer-nonce' ) // phpcs:ignore
        ) {
            wp_send_json_error( __( 'Error: Nonce verification failed', 'dokan' ) );
        }

        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $api = plugins_api(
            'plugin_information', array(
                'slug'   => 'seo-by-rank-math',
                'fields' => array(
                    'sections' => false,
                ),
            )
        );

        $upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );
        $upgrader->install( $api->download_link );
        activate_plugin( 'seo-by-rank-math/rank-math.php' );

        wp_send_json_success();
    }

    /**
     * Checks if Rank Math SEO plugin is installed
     *
     * @since 3.4.0
     *
     * @return bool
     */
    private function is_rank_math_installed() {
        $plugins = array_keys( get_plugins() );

        return in_array( 'seo-by-rank-math/rank-math.php', $plugins, true );
    }

    /**
     * Defines the required constants
     *
     * @since 3.4.0
     *
     * @return void
     */
    private function constants() {
        define( 'DOKAN_RANK_MATH_FILE', __FILE__ );
        define( 'DOKAN_RANK_MATH_PATH', dirname( DOKAN_RANK_MATH_FILE ) );
        define( 'DOKAN_RANK_MATH_TEMPLATE_PATH', dirname( DOKAN_RANK_MATH_FILE ) . '/templates/' );
    }

    /**
     * Registers required hooks
     *
     * @since 3.4.0
     *
     * @return void
     */
    public function hooks() {
        // Load SEO content after inventory variants widget on the edit product page
        add_action( 'dokan_product_edit_after_inventory_variants', array( $this, 'load_product_seo_content' ), 6, 2 );

        // Map meta cap for `vendor_staff` to bypass some primitive capability requirements.
        add_filter( 'map_meta_cap', array( $this, 'map_meta_cap_for_rank_math' ), 10, 4 );
    }

    /**
     * Maps meta cap for users with vendor staff role to bypass some primitive
     * capability requirements.
     *
     * To access the rank math rest api functionality, a user must have one or some
     * primitive capabilities which are `edit_products`, `edit_published_products`,
     * `edit_others_products`, and `edit_private_products`
     *
     * Often users with `vendor_staff` role miss those required capabilities that
     * would lead them to being unable to update the product although they are given
     * permission to edit product.
     *
     * So to ensure their ability to update product and to use the Rank Math SEO
     * functionalities, the required premitive capabilities are bypassed.
     *
     * Note that it is ensured the capabilities will be bypassed only while
     * the rest api endpoint for Rank Math SEO is being hit.
     *
     * Also for rank math redirection settings, all users need to have the
     * capability of `rank_math_redirections`. So it needs to be ensured all users
     * are given that capability while updating the rank math redirection settings
     * for products.
     *
     * @since 3.4.0
     *
     * @uses global   $wp          Used to retrieve \WP class data
     * @uses function get_userdata Used to retrieve userdata by id
     *
     * @param array   $caps    Premitive capabilities that must be possessed by user
     * @param string  $cap     Capability that is mapping the premitive capabilities
     * @param integer $user_id ID of the current user
     *
     * @return array List of premitive capabilities to be satisfied
     */
    public function map_meta_cap_for_rank_math( $caps, $cap, $user_id, $args ) {
        global $wp;

        if (
            empty( $wp->query_vars['rest_route'] ) ||
            false === strpos( $wp->query_vars['rest_route'], \RankMath\Rest\Rest_Helper::BASE )
        ) {
            return $caps;
        }

        if ( 'edit_others_products' === $cap ) {
            /**
             * Here the userdata is being retrieved
             * to get all capabilities of the user
             * in order to check specific capability
             * like `vendor_staff`.
             */
            $user = get_userdata( $user_id );

            // Bypass the primitive caps only if the user is `vendor_staff`
            if ( ! empty( $user->allcaps['vendor_staff'] ) ) {
                return array();
            }
        } elseif ( 'rank_math_redirections' === $cap ) {
            /**
             * For Redirection user need to have the capability
             * of `rank_math_redirections`. So here the users
             * who can edit dokan products are given that
             * capability so that they can edit redierct settings.
             */
            add_filter(
                'user_has_cap', function( $all_caps ) {
                    $all_caps['rank_math_redirections'] = true;
                    return $all_caps;
                }, 10, 1
            );
        }

        return $caps;
    }

    /**
     * Loads rank math seo content for product update
     *
     * @since 3.4.0
     *
     * @param object $product
     * @param int $product_id
     *
     * @return void
     */
    public function load_product_seo_content( $product, $product_id ) {

        /**
         * Process the required functionality
         * for frontend application including
         * all the styles and scripts
         */
        $frontend = new Frontend();
        $frontend->process();

        // Require the template for rank math seo content
        require_once DOKAN_RANK_MATH_TEMPLATE_PATH . 'product-seo-content.php';
    }
}
