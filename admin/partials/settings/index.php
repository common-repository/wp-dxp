<?php

/**
 * Provides a settings page view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 * This file should primarily consist of HTML with a little bit of PHP
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/admin/partials
 */

$signed = get_option( WP_DXP_NEWSLETTER_SIGNUP_OPTION_KEY );

require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/header.php'; ?>

<section class="section wp-dxp-body-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 col-md-12 col-lg-8 col-xl-9">
                <form id="wp-dxp-newsletter-signup" class="wp-dxp-panel<?php echo ( $signed ? ' signed' : '' ); ?>" action="" method="post">
                    <h2 class="wp-dxp-panel__title"><?php echo esc_html__('Join our email list', 'wp-dxp'); ?></h2>

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 wp-dxp-input-group">
                            <label for="input-first-name"><?php echo esc_html__('First name', 'wp-dxp'); ?></label>
                            <input id="input-first-name" type="text" name="first_name" value="" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 wp-dxp-input-group">
                            <label for="input-last-name"><?php echo esc_html__('Last name', 'wp-dxp'); ?></label>
                            <input id="input-last-name" type="text" name="last_name" value="" />
                        </div>
                    </div>
                    <div class="wp-dxp-input-group">
                        <label for="input-email-address"><?php echo esc_html__('Email address', 'wp-dxp'); ?></label>
                        <input id="input-email-address" type="email" name="email_address" value="" />
                    </div>
                    <div class="wp-dxp-input-group">
                        <label for="input-terms">
                            <input id="input-terms" type="checkbox" name="terms_acceptance" value="1" />
                            <?php
							printf(
								/* translators: 1: %s expands to a website link to WP-DXP Terms and Conditions, 2: </a> closing tag. */
								esc_html__( 'I accept the %1$sTerms and Conditions%2$s', 'wp-dxp' ),
								'<a href="' . esc_url( 'https://personalizewp.com/terms/' ) . '" target="_blank">',
								'</a>'
							);
							?>
                        </label>
                    </div>
                    <div class="wp-dxp-flex">
                        <button class="wp-dxp-button" type="submit"><?php echo esc_html__('Sign up', 'wp-dxp'); ?></button>

                        <?php if ( !empty($_GET['status']) && 'activated' === sanitize_key($_GET['status']) ): ?>
                            <a href="<?php echo esc_url(WP_DXP_ADMIN_DASHBOARD_INDEX_URL); ?>"><?php echo esc_html__('Skip for now', 'wp-dxp'); ?></a>
                        <?php endif; ?>
                    </div>
                </form>

                <div id="wp-dxp-newsletter-signup-success" class="wp-dxp-panel">
                    <h2 class="wp-dxp-panel__title"><?php echo esc_html__('You’re on the list!', 'wp-dxp'); ?></h2>
		            <p><?php echo esc_html__('Now you’re on our email list, we’ll share tips and tricks of things you can do with WP-DXP, as well as inform you when new versions and features are added to the plugin. If you’d like to opt-out of receiving email, then click on the Manage Preference link at the bottom of any emails we send you.', 'wp-dxp'); ?></p>
                </div>
            </div>

            <aside class="wp-dxp-cta-sidebar col-12 col-md-12 col-lg-4 col-xl-3">
                <?php if ( !empty($items) ):
                    foreach ( $items as $item ):
                        include plugin_dir_path( dirname( __FILE__ ) ) . '/_other/cta.php';
                    endforeach;
                endif; ?>
            </aside>
        </div>
    </div>
</section>

<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/footer.php';
