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
                <h2 class="h4 section-title mb-2">
                    <?php
                    printf(
                        /* translators: 1: %s category name. */
                        esc_html__( 'Edit Category: %s', 'wp-dxp' ),
                        $category->name
                    );
                    ?>
                </h2>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <p class="section-description"><?php esc_html_e( 'Edit the category details using the form below. When finished, click on Save to update the category.', 'wp-dxp' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-10">

				<form action="<?php echo $category->edit_url; ?>" method="post" accept-charset="UTF-8" id="wp-dxp-rule-form">
					<?php
					include_once('_form.php'); ?>
				</form>

            </div>
        </div>
    </div>
</section>

<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/footer.php';
