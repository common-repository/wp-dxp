<?php

define('WP_DXP_TAG','wp-dxp');

// Newsletter API
define('WP_DXP_API', 'https://personalizewp.com/wp-json/personalizewp/v1/submission');

// Slugs
define('WP_DXP_ADMIN_SLUG', 'wp-dxp');
define('WP_DXP_ADMIN_SETTINGS_SLUG', 'wp-dxp/settings');
define('WP_DXP_ADMIN_KNOWLEDGE_BASE_SLUG', 'wp-dxp/knowledge-base');
define('WP_DXP_ADMIN_CATEGORIES_SLUG', 'wp-dxp/categories');
define('WP_DXP_ADMIN_RULES_SLUG', 'wp-dxp/rules');
define('WP_DXP_ADMIN_URL', get_admin_url());

// Pages
define('WP_DXP_ADMIN_DASHBOARD_INDEX_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_SLUG);

define('WP_DXP_ADMIN_CATEGORIES_INDEX_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_CATEGORIES_SLUG);
define('WP_DXP_ADMIN_CATEGORIES_CREATE_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_CATEGORIES_SLUG . '&wp_dxp_action=create');
define('WP_DXP_ADMIN_CATEGORIES_EDIT_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_CATEGORIES_SLUG . '&wp_dxp_action=edit&id=');
define('WP_DXP_ADMIN_CATEGORIES_DELETE_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_CATEGORIES_SLUG . '&wp_dxp_action=delete&id=');
define('WP_DXP_ADMIN_CATEGORIES_SHOW_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_CATEGORIES_SLUG . '&wp_dxp_action=show&id=');
define('WP_DXP_ADMIN_CATEGORIES_RULES_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_CATEGORIES_SLUG . '&wp_dxp_action=rules&id=');

define('WP_DXP_ADMIN_RULES_INDEX_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_RULES_SLUG);
define('WP_DXP_ADMIN_RULES_CREATE_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_RULES_SLUG . '&wp_dxp_action=create');
define('WP_DXP_ADMIN_RULES_EDIT_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_RULES_SLUG . '&wp_dxp_action=edit&id=');
define('WP_DXP_ADMIN_RULES_DELETE_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_RULES_SLUG . '&wp_dxp_action=delete&id=');
define('WP_DXP_ADMIN_RULES_DUPLICATE_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_RULES_SLUG . '&wp_dxp_action=duplicate&id=');
define('WP_DXP_ADMIN_RULES_SHOW_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_RULES_SLUG . '&wp_dxp_action=show&id=');

define('WP_DXP_ADMIN_SETTINGS_INDEX_URL', WP_DXP_ADMIN_URL . 'admin.php?page=' . WP_DXP_ADMIN_SETTINGS_SLUG);

define('WP_DXP_ADMIN_KNOWLEDGE_BASE_URL', 'https://personalizewp.com/knowledge-base/');
define('WP_DXP_ADMIN_SUPPORT_URL', 'https://wordpress.org/support/plugin/wp-dxp/');
define('WP_DXP_ADMIN_REVIEWS_URL', 'https://en-gb.wordpress.org/plugins/wp-dxp/#reviews');

// Flash notices
define('WP_DXP_FLASH_MESSAGES_OPTION_KEY', 'wp_dxp_flash_messages');
define('WP_DXP_ADMIN_NOTICES_OPTION_KEY', 'wp_dxp_admin_notices');
define('WP_DXP_DASHBOARD_MESSAGE_OPTION_KEY', 'wp_dxp_admin_dashboard_message');
define('WP_DXP_ONBOARDING_MESSAGE_OPTION_KEY', 'wp_dxp_admin_onboarding_message');


// DB Tables
define('WP_DXP_TABLE_CATEGORIES', 'wp_dxp_categories');
define('WP_DXP_TABLE_RULES', 'wp_dxp_rules');
define( 'WP_DXP_TABLE_ACTIVE_BLOCKS', 'dxp_active_blocks' );

// Forms
define('WP_DXP_FORM_NONCE_FIELD_NAME', 'wp_dxp_nonce');
define('WP_DXP_FORM_NONCE_FIELD_ACTION', 'wp_dxp');
define('WP_DXP_FORM_FIELD_PREFIX', 'wp_dxp_form');


// Rule/Conditions
define('WP_DXP_VISITOR_COOKIE_NAME', 'wp-dxp-visitor');
define('WP_DXP_VISITOR_COOKIE_EXPIRY', 84600);

// MaxMind
define('WP_DXP_MAXMIND_DB', plugin_dir_path( dirname( __FILE__ ) ) . 'GeoLite2-Country.mmdb');

// Options Keys
define('WP_DXP_EDITOR_ROLE_VALUES_KEY', 'wp_dxp_editor_role_values');
define('WP_DXP_NEWSLETTER_SIGNUP_OPTION_KEY', 'wp_dxp_newsletter_signup');
