<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Export_Wp_Page_To_Static_Html
 * @subpackage Export_Wp_Page_To_Static_Html/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Export_Wp_Page_To_Static_Html
 * @subpackage Export_Wp_Page_To_Static_Html/includes
 * @author     ReCorp <rayhankabir1000@gmail.com>
 */
class Export_Wp_Page_To_Static_Html_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

	    $table_name = "export_page_to_html_logs";

	    $res = $wpdb->get_results("SELECT id FROM " . $wpdb->prefix . $table_name );

	    if (empty($res)) {
	    	
			$charset_collate = $wpdb->get_charset_collate();
	    update_option($table_name, ABSPATH . 'wp-admin/includes/upgrade.php');

			$sql = "CREATE TABLE {$wpdb->prefix}{$table_name} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			order_id smallint(5) NOT NULL,
			type text NOT NULL,
			path text NOT NULL,
			comment text NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
	    }

	}

}
