<?php
/**
 * Main Admin page header template
 */

$currentPage = $this->request->get('page');
switch ($currentPage) {
    case WP_DXP_ADMIN_SLUG:
	    $currentPage = esc_html__('Dashboard', 'wp-dxp');
        break;

	case WP_DXP_ADMIN_RULES_SLUG:
	case WP_DXP_ADMIN_CATEGORIES_SLUG:
		$currentPage = esc_html__('Personalization', 'wp-dxp');
		break;

    default:
		$currentPage = esc_html__('Settings', 'wp-dxp');
	    if ( isset($_GET['status']) && 'activated' === sanitize_key($_GET['status']) ) {
		    $currentPage = esc_html__('Onboarding', 'wp-dxp');
	    }
		break;
} ?>
<!-- CSS namespace -->
<div id="wp-dxp">

<header class="wp-dxp-header page-<?php echo sanitize_title($this->request->get('page')); ?>">
    <img class="wp-dxp-header__bg" src="<?php echo plugins_url('../../img/header-bg.svg', __FILE__); ?>" alt="" />

	<div  class="wp-dxp-breadcrumbs">
		<h1 class="wp-dxp-breadcrumbs__title">
            <img src="<?php echo plugins_url('../../img/wp-dxp-logo.svg', __FILE__); ?>" alt="<?php echo esc_html( 'WP-DXP', 'wp-dxp' ); ?>" />
        </h1>
        <span class="wp-dxp-breadcrumbs__separator">/</span>
        <span class="wp-dxp-breadcrumbs__page"><?php echo $currentPage; ?></span>
	</div>

    <div class="wp-dxp-header__links">
        <a href="<?php echo esc_url( WP_DXP_ADMIN_KNOWLEDGE_BASE_URL ); ?>" target="_blank" class="wp-dxp-button wp-dxp-button--white">
            <i class="bi bi-question-circle"></i>
            <?php echo esc_html__('Help', 'wp-dxp'); ?>
        </a>
    </div>
</header>


<?php $this->displayFlashMessages();
