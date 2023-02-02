<?php

namespace WeDevs\DokanPro\Modules\DeliveryTime\Emails;

class Manager {

    /**
     * Manager constructor.
     *
     * @since 3.7.8
     */
    public function __construct() {
        add_filter( 'woocommerce_email_classes', [ $this, 'load_delivery_time_emails' ] );
        add_filter( 'dokan_email_list', [ $this, 'set_delivery_time_email_template_directory' ] );
        add_filter( 'dokan_email_actions', [ $this, 'register_delivery_time_email_actions' ] );
    }

    /**
     * Load delivery time related emails.
     *
     * @since 3.7.8
     *
     * @param array $wc_emails
     *
     * @return array
     */
    public function load_delivery_time_emails( $wc_emails ) {
        $wc_emails['Dokan_Email_Admin_Update_Order_Delivery_Time']  = new UpdateAdminOrderDeliveryTime();
        $wc_emails['Dokan_Email_Vendor_Update_Order_Delivery_Time'] = new UpdateVendorOrderDeliveryTime();

        return $wc_emails;
    }

    /**
     * Set email template directory from here.
     *
     * @since 3.7.8
     *
     * @param array $dokan_emails
     *
     * @return array
     */
    public function set_delivery_time_email_template_directory( $dokan_emails ) {
        $delivery_time_emails[] = 'update-admin-order-time-email.php';
        $delivery_time_emails[] = 'update-vendor-order-time-email.php';

        return array_merge( $delivery_time_emails, $dokan_emails );
    }

    /**
     * Register Dokan Delivery Time Email actions.
     *
     * @since 3.7.8
     *
     * @param array $actions
     *
     * @return array
     */
    public function register_delivery_time_email_actions( $actions ) {
        $actions[] = 'dokan_after_vendor_update_order_delivery_info';
        $actions[] = 'dokan_after_admin_update_order_delivery_info';

        return $actions;
    }
}
