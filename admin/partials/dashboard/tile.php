<?php

/**
 * View for single tile located in Dashboard screen
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 * This file should primarily consist of HTML with a little bit of PHP
 *
 * @link       http://example.com
 * @since      1.6.0
 *
 * @package    Wp_Dxp
 * @subpackage Wp_Dxp/admin/partials
 */

?>

<div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
	<<?php echo empty($item['url']) ? 'div' : 'a href="'. $item['url'] . '"'; ?> class="wp-dxp-link-tile<?php echo empty($item['url']) ? ' wp-dxp-link-tile--disabled' : ''; ?>">
		<div class="wp-dxp-link-tile__thumbnail">
			<?php if ( empty($item['url']) ): ?>
                <div class="wp-dxp-link-tile__banner"><?php echo __('Coming soon', 'wp-dxp'); ?></div>
			<?php endif; ?>

			<img class="wp-dxp-link-tile__thumbnail__img" src="<?php echo $item['img']; ?>" />
		</div>
		<div class="wp-dxp-link-tile__content">
			<div class="wp-dxp-link-tile__content__text">
				<span class="wp-dxp-link-tile__title"><?php echo $item['title']; ?></span>
				<p><?php echo $item['content']; ?></p>
			</div>
		</div>
	</<?php echo empty($item['url']) ? 'div' : 'a'; ?>>
</div>
