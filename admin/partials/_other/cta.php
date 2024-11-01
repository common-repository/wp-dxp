<?php
/**
 * CTA tile template
 */
?>

<div class="wp-dxp-cta-tile wp-dxp-link-tile">
	<div class="wp-dxp-link-tile__thumbnail">
		<img class="wp-dxp-link-tile__thumbnail__img" src="<?php echo $item['img']; ?>" />
	</div>
	<div class="wp-dxp-link-tile__content">
		<div class="wp-dxp-link-tile__content__text">
			<span class="wp-dxp-link-tile__title"><?php echo $item['title']; ?></span>
			<p><?php echo $item['content']; ?></p>
		</div>

		<a class="wp-dxp-button" href="<?php echo $item['url']; ?>"<?php echo ( strpos($item['url'], home_url()) === false ? ' target="_blank"' : '' ); ?>><?php echo $item['button']; ?></a>
	</div>
</div>
