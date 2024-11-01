<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 * This file should primarily consist of HTML with a little bit of PHP.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/admin/partials
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/header.php'; ?>


<div class="container-fluid pl-0 pr-0 wp-dxp-header-nav">
    <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'personalisation/nav.php'; ?>
</div>


<section class="section mb-0 wp-dxp-body-content">
    <div class="container-fluid">
        <div class="row mb-lg-2">
            <div class="col-12 d-inline-flex align-items-center justify-content-between">
                <h2 class="h4 section-title mb-2"><?php esc_html_e( 'Add New Category', 'wp-dxp' ); ?></h2>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <p class="section-description"><?php esc_html_e( 'Create a new category using the options below.', 'wp-dxp' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-fluid">
        <div class="row mb-lg-2">
            <div class="col-12 col-lg-10">

                <form action="<?php echo WP_DXP_ADMIN_CATEGORIES_CREATE_URL; ?>" method="post" accept-charset="UTF-8" id="wp-dxp-rule-form">
                    <?php
                    include_once('_form.php'); ?>
                </form>

            </div>
        </div>
    </div>
</section>

<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/footer.php';
