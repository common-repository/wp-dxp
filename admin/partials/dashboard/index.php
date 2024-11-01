<?php

/**
 * Provide a admin area view for the plugin
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

require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/header.php'; ?>

    <section class="section wp-dxp-body-content">
        <div class="container-fluid">
            <div class="row">
				<?php foreach ( $items as $item ):
                    include plugin_dir_path( dirname( __FILE__ ) ) . '/dashboard/tile.php';
                endforeach; ?>
            </div>

			<?php if ( !empty($comingSoon) ): ?>
                <div class="row">
					<?php foreach ( $comingSoon as $item ):
						include plugin_dir_path( dirname( __FILE__ ) ) . '/dashboard/tile.php';
                    endforeach; ?>
                </div>
			<?php endif; ?>
        </div>
    </section>

<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/footer.php';
