<?php

/**
 * Fired during plugin activation
 *
 * @link       stpetedesign.com
 * @since      1.0.0
 *
 * @package    Stp_Srtc
 * @subpackage Stp_Srtc/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Stp_Srtc
 * @subpackage Stp_Srtc/includes
 * @author     stpetedesign.com <foucciano@gmail.com >
 */
class Stp_Srtc_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        self::create_employee_table();
        self::create_checklist_table();
        /* this sets the url of custom post type */
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
	}

	public static function create_employee_table()
	{
    global $wpdb;
    $table_name = $wpdb->prefix . 'employee';
    $wpdb_collate = $wpdb->collate;
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    $sql =
        "CREATE TABLE {$table_name} (
        employee_id mediumint(8) unsigned NOT NULL auto_increment ,
        name varchar(124) NOT NULL,
        code varchar(32) NOT NULL,
        status boolean DEFAULT 0,
        PRIMARY KEY  (employee_id),
        UNIQUE KEY (code)
        )
        COLLATE {$wpdb_collate}";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta( $sql );
        }
    }
    
    public static function create_checklist_table()
	{
    global $wpdb;
    $table_name = $wpdb->prefix . 'checklist';
    $wpdb_collate = $wpdb->collate;
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    $sql =
        "CREATE TABLE {$table_name} (
        record_id mediumint(8) unsigned NOT NULL auto_increment ,
        employee_id mediumint(8) unsigned NOT NULL,
        checklist_id mediumint(8) unsigned NOT NULL,
        date Date NOT NULL,
        name varchar(124) NOT NULL,
        original_list MEDIUMTEXT NOT NULL,
        selected_items MEDIUMTEXT,
        PRIMARY KEY  (record_id),
        UNIQUE KEY (employee_id, checklist_id, date)
        )
        COLLATE {$wpdb_collate}";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta( $sql );
        }
	}

}
