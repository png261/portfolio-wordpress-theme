<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.upwork.com/fl/rayhan1
 * @since             1.0.0
 * @package           Export_Wp_Page_To_Static_Html
 *
 * @wordpress-plugin
 * Plugin Name:       Export WP Page to Static HTML/CSS
 * Plugin URI:        https://myrecorp.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            ReCorp
 * Author URI:        https://www.upwork.com/fl/rayhan1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       export-wp-page-to-static-html
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EXPORT_WP_PAGE_TO_STATIC_HTML_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-export-wp-page-to-static-html-activator.php
 */
function activate_export_wp_page_to_static_html() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-export-wp-page-to-static-html-activator.php';
	Export_Wp_Page_To_Static_Html_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-export-wp-page-to-static-html-deactivator.php
 */
function deactivate_export_wp_page_to_static_html() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-export-wp-page-to-static-html-deactivator.php';
	Export_Wp_Page_To_Static_Html_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_export_wp_page_to_static_html' );
register_deactivation_hook( __FILE__, 'deactivate_export_wp_page_to_static_html' );

register_activation_hook(__FILE__, 'export_wp_page_to_html_save_redirect_option');
add_action('admin_init', 'export_wp_page_to_html_redirect_to_menu');


/*Activating daily task*/
register_activation_hook( __FILE__, 'rc_static_html_task_events_activate' );
register_deactivation_hook( __FILE__, 'rc_static_html_task_events_deactivate' );


/*Redirect to plugin's settings page when plugin will active*/
function export_wp_page_to_html_save_redirect_option() {
    add_option('export_wp_page_to_html_activation_check', true);
}


function export_wp_page_to_html_redirect_to_menu() {
    if (get_option('export_wp_page_to_html_activation_check', false)) {
        delete_option('export_wp_page_to_html_activation_check');
         exit( wp_redirect("options-general.php?page=export-wp-page-to-html&welcome=true") );
    }
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-export-wp-page-to-static-html.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_export_wp_page_to_static_html() {

	$plugin = new Export_Wp_Page_To_Static_Html();
	$plugin->run();

}
run_export_wp_page_to_static_html();
