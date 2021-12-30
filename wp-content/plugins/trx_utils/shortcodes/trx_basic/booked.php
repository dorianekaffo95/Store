<?php

if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
    add_action('jardiwinery_action_shortcodes_list',				'jardiwinery_booked_reg_shortcodes');
    add_action('jardiwinery_action_shortcodes_list_vc',		'jardiwinery_booked_reg_shortcodes_vc');


// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('jardiwinery_booked_reg_shortcodes')) {

    function jardiwinery_booked_reg_shortcodes() {
        if (jardiwinery_storage_isset('shortcodes')) {

            $booked_cals = jardiwinery_get_list_booked_calendars();

            jardiwinery_sc_map('booked-appointments', array(
                    "title" => esc_html__("Booked Appointments", "jardiwinery"),
                    "desc" => esc_html__("Display the currently logged in user's upcoming appointments", "jardiwinery"),
                    "decorate" => true,
                    "container" => false,
                    "params" => array()
                )
            );

            jardiwinery_sc_map('booked-calendar', array(
                "title" => esc_html__("Booked Calendar", "jardiwinery"),
                "desc" => esc_html__("Insert booked calendar", "jardiwinery"),
                "decorate" => true,
                "container" => false,
                "params" => array(
                    "calendar" => array(
                        "title" => esc_html__("Calendar", "jardiwinery"),
                        "desc" => esc_html__("Select booked calendar to display", "jardiwinery"),
                        "value" => "0",
                        "type" => "select",
                        "options" => jardiwinery_array_merge(array(0 => esc_html__('- Select calendar -', 'jardiwinery')), $booked_cals)
                    ),
                    "year" => array(
                        "title" => esc_html__("Year", "jardiwinery"),
                        "desc" => esc_html__("Year to display on calendar by default", "jardiwinery"),
                        "value" => date("Y"),
                        "min" => date("Y"),
                        "max" => date("Y")+10,
                        "type" => "spinner"
                    ),
                    "month" => array(
                        "title" => esc_html__("Month", "jardiwinery"),
                        "desc" => esc_html__("Month to display on calendar by default", "jardiwinery"),
                        "value" => date("m"),
                        "min" => 1,
                        "max" => 12,
                        "type" => "spinner"
                    )
                )
            ));
        }
    }
}


// Register shortcode in the VC shortcodes list
if (!function_exists('jardiwinery_booked_reg_shortcodes_vc')) {

    function jardiwinery_booked_reg_shortcodes_vc() {

        $booked_cals = jardiwinery_get_list_booked_calendars();

        // Booked Appointments
        vc_map( array(
            "base" => "booked-appointments",
            "name" => esc_html__("Booked Appointments", "jardiwinery"),
            "description" => esc_html__("Display the currently logged in user's upcoming appointments", "jardiwinery"),
            "category" => esc_html__('Content', 'jardiwinery'),
            'icon' => 'icon_trx_booked',
            "class" => "trx_sc_single trx_sc_booked_appointments",
            "content_element" => true,
            "is_container" => false,
            "show_settings_on_create" => false,
            "params" => array()
        ) );

        class WPBakeryShortCode_Booked_Appointments extends Jardiwinery_Vc_ShortCodeSingle {}

        // Booked Calendar
        vc_map( array(
            "base" => "booked-calendar",
            "name" => esc_html__("Booked Calendar", "jardiwinery"),
            "description" => esc_html__("Insert booked calendar", "jardiwinery"),
            "category" => esc_html__('Content', 'jardiwinery'),
            'icon' => 'icon_trx_booked',
            "class" => "trx_sc_single trx_sc_booked_calendar",
            "content_element" => true,
            "is_container" => false,
            "show_settings_on_create" => true,
            "params" => array(
                array(
                    "param_name" => "calendar",
                    "heading" => esc_html__("Calendar", "jardiwinery"),
                    "description" => esc_html__("Select booked calendar to display", "jardiwinery"),
                    "admin_label" => true,
                    "class" => "",
                    "std" => "0",
                    "value" => array_flip(jardiwinery_array_merge(array(0 => esc_html__('- Select calendar -', 'jardiwinery')), $booked_cals)),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "year",
                    "heading" => esc_html__("Year", "jardiwinery"),
                    "description" => esc_html__("Year to display on calendar by default", "jardiwinery"),
                    "admin_label" => true,
                    "class" => "",
                    "std" => date("Y"),
                    "value" => date("Y"),
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "month",
                    "heading" => esc_html__("Month", "jardiwinery"),
                    "description" => esc_html__("Month to display on calendar by default", "jardiwinery"),
                    "admin_label" => true,
                    "class" => "",
                    "std" => date("m"),
                    "value" => date("m"),
                    "type" => "textfield"
                )
            )
        ) );

        class WPBakeryShortCode_Booked_Calendar extends Jardiwinery_Vc_ShortCodeSingle {}

    }
}