<?php
/**
 * Extrch.co Link Page Analytics - Database Management
 *
 * Handles creation and updates for the custom analytics database table.
 */

defined('ABSPATH') || exit;

define('EXTRCH_ANALYTICS_DB_VERSION', '1.1');
define('EXTRCH_ANALYTICS_DB_VERSION_OPTION', 'extrch_analytics_db_version');

/**
 * Creates or updates analytics tables with version checking.
 */
function extrch_create_or_update_analytics_table() {
    $current_db_version = get_option(EXTRCH_ANALYTICS_DB_VERSION_OPTION);

    if ($current_db_version === EXTRCH_ANALYTICS_DB_VERSION) {
        return;
    }

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $table_views = $wpdb->prefix . 'extrch_link_page_daily_views';
    $sql_views = "CREATE TABLE {$table_views} (
        view_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        link_page_id bigint(20) unsigned NOT NULL,
        stat_date date NOT NULL,
        view_count bigint(20) unsigned NOT NULL DEFAULT 0,
        PRIMARY KEY  (view_id),
        UNIQUE KEY unique_daily_view (link_page_id, stat_date)
    ) {$charset_collate};";

    $table_clicks = $wpdb->prefix . 'extrch_link_page_daily_link_clicks';
    $sql_clicks = "CREATE TABLE {$table_clicks} (
        click_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        link_page_id bigint(20) unsigned NOT NULL,
        stat_date date NOT NULL,
        link_url varchar(2083) NOT NULL,
        click_count bigint(20) unsigned NOT NULL DEFAULT 0,
        PRIMARY KEY  (click_id),
        UNIQUE KEY unique_daily_link_click (link_page_id, stat_date, link_url(191)),
        KEY link_page_date (link_page_id, stat_date)
    ) {$charset_collate};";

    dbDelta($sql_views);
    dbDelta($sql_clicks);

    update_option(EXTRCH_ANALYTICS_DB_VERSION_OPTION, EXTRCH_ANALYTICS_DB_VERSION);

}

add_action('admin_init', 'extrch_create_or_update_analytics_table');

?> 