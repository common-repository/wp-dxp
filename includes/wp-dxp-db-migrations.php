<?php
defined( 'ABSPATH' ) || exit;

$wpDxpDbMigrations = [
	function ($wpdb) {
		$charset_collate = $wpdb->get_charset_collate();
		$wpdb->query("
            CREATE TABLE `{$wpdb->prefix}wp_dxp_categories` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL DEFAULT '',
                `created_at` datetime NOT NULL,
                `modified_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) {$charset_collate};");
	},
	function ($wpdb) {
		$charset_collate = $wpdb->get_charset_collate();
		$wpdb->query("
            CREATE TABLE `{$wpdb->prefix}wp_dxp_rules` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL DEFAULT '',
                `category_id` int(11) NOT NULL,
                `type` varchar(30) NOT NULL,
                `conditions_json` mediumtext NOT NULL,
                `created_by` bigint(20) NOT NULL,
                `created_at` datetime NOT NULL,
                `modified_at` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) {$charset_collate};");
	},
    function ($wpdb) {
        $wpdb->query("
            INSERT INTO `{$wpdb->prefix}wp_dxp_categories` (`id`, `name`, `created_at`, `modified_at`)
            VALUES
                (NULL, 'Location', NOW(), NOW()),
                (NULL, 'Purchases', NOW(), NOW()),
                (NULL, 'User Types', NOW(), NOW()),
                (NULL, 'Other', NOW(), NOW())");
    },
    function ($wpdb) {
        $wpdb->query("
        INSERT INTO `{$wpdb->prefix}wp_dxp_rules` (`id`, `name`, `category_id`, `type`, `conditions_json`, `created_by`, `created_at`, `modified_at`)
        VALUES
            (NULL, 'User is currently logged in', 3, 'standard', '[{\"measure\":\"core_is_logged_in_user\",\"comparator\":\"equals\",\"value\":\"true\"}]', 0, NOW(), NOW()),
            (NULL, 'User is not logged in', 3, 'standard', '[{\"measure\":\"core_is_logged_in_user\",\"comparator\":\"equals\",\"value\":\"false\"}]', 0, NOW(), NOW()),
            (NULL, 'UK-based visitor', 1, 'standard', '[{\"measure\":\"core_visitor_country\",\"comparator\":\"equals\",\"value\":\"GB\"}]', 0, NOW(), NOW());
        ");
    },
    function ($wpdb) {
        $wpdb->query("
            INSERT INTO `{$wpdb->prefix}wp_dxp_categories` (`id`, `name`, `created_at`, `modified_at`)
            VALUES
                (NULL, 'Device Types', NOW(), NOW()),
                (NULL, 'Time', NOW(), NOW());");
    },
    function ($wpdb) {
        $wpdb->query("
        INSERT INTO `{$wpdb->prefix}wp_dxp_rules` (`id`, `name`, `category_id`, `type`, `conditions_json`, `created_by`, `created_at`, `modified_at`)
        VALUES
            (NULL, 'New visitor', 3, 'standard', '[{\"measure\":\"core_new_visitor\",\"comparator\":\"equals\",\"value\":\"true\"}]', 0, NOW(), NOW()),
            (NULL, 'Returning visitor', 3, 'standard', '[{\"measure\":\"core_new_visitor\",\"comparator\":\"equals\",\"value\":\"false\"}]', 0, NOW(), NOW()),
            (NULL, 'Device Type - mobile', 5, 'standard', '[{\"measure\":\"core_users_device_type\",\"comparator\":\"equals\",\"value\":\"mobile\"}]', 0, NOW(), NOW()),
            (NULL, 'Device Type - desktop', 5, 'standard', '[{\"measure\":\"core_users_device_type\",\"comparator\":\"equals\",\"value\":\"desktop\"}]', 0, NOW(), NOW()),
            (NULL, '10 secs spent on page', 6, 'standard', '[{\"measure\":\"core_time_elapsed\",\"comparator\":\"equals\",\"value\":\"10\"}]', 0, NOW(), NOW()),
            (NULL, '30 secs spent on page', 6, 'standard', '[{\"measure\":\"core_time_elapsed\",\"comparator\":\"equals\",\"value\":\"30\"}]', 0, NOW(), NOW()),
            (NULL, '1 min spent on page', 6, 'standard', '[{\"measure\":\"core_time_elapsed\",\"comparator\":\"equals\",\"value\":\"60\"}]', 0, NOW(), NOW()),
            (NULL, 'US-based visitor', 1, 'standard', '[{\"measure\":\"core_visitor_country\",\"comparator\":\"equals\",\"value\":\"US\"}]', 0, NOW(), NOW()),
            (NULL, 'Time is morning (6am - 12pm)', 6, 'standard', '[{\"measure\":\"core_users_visiting_time\",\"comparator\":\"equals\",\"value\":\"morning\"}]', 0, NOW(), NOW()),
            (NULL, 'Time is afternoon (12pm - 6pm)', 6, 'standard', '[{\"measure\":\"core_users_visiting_time\",\"comparator\":\"equals\",\"value\":\"afternoon\"}]', 0, NOW(), NOW()),
            (NULL, 'Time is evening (6pm - 12am)', 6, 'standard', '[{\"measure\":\"core_users_visiting_time\",\"comparator\":\"equals\",\"value\":\"evening\"}]', 0, NOW(), NOW());
        ");
    },
    function ($wpdb) {
        $wpdb->query("
        UPDATE `{$wpdb->prefix}wp_dxp_rules`
        SET
            name = 'UK-based visitor'
        WHERE
            name = 'UK Visitors only';
        ");
    },
    function ($wpdb) {
        $wpdb->query("
        UPDATE `{$wpdb->prefix}wp_dxp_rules`
        SET
            name = 'Non-UK-based visitor'
        WHERE
            name = 'Non-UK visitors';
        ");
    },
    function ($wpdb) {

		$table_name = "{$wpdb->prefix}dxp_active_blocks";

		$collate = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';
		$sql = "
            CREATE TABLE {$wpdb->prefix}dxp_active_blocks (
                `id` bigint(20) unsigned NOT NULL default 0,
                `rule_id` bigint(20) unsigned NOT NULL default 0,
                `post_id` bigint(20) unsigned NOT NULL default 0,
                `name` varchar(255) NOT NULL DEFAULT '',
				PRIMARY KEY (`id`, `rule_id`, `post_id`),
                KEY rule_id (`rule_id`),
                KEY post_id (`post_id`)
            ) {$collate};
		";
		$this->maybe_create_table( $table_name, $sql );
	},
];

