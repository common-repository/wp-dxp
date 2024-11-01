<?php
$currentPage = $this->request->get('page'); ?>
<div class="row">
    <div class="col-12">
        <header class="header">
            <nav class="header-nav">
                <ul class="nav-list d-inline-flex">
                    <li class="nav-item">
                        <?php 
                            $action = $this->request->get('wp_dxp_action'); 

                            $navPageActive = false;

                            if ($currentPage == WP_DXP_ADMIN_RULES_SLUG) {
                                $navPageActive = true;
                            } elseif ($action != 'edit' && !empty($rule)) {
                                $navPageActive = true;
                            } elseif ($action == 'edit' && !empty($rule)) {
                                $navPageActive = true;
                            }
                        ?>
                        <a class="nav-link <?php echo $navPageActive ? 'active' : ''; ?>" href="<?php echo WP_DXP_ADMIN_RULES_INDEX_URL; ?>"> <?php esc_html_e( 'Rules', 'wp-dxp' ); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == WP_DXP_ADMIN_CATEGORIES_SLUG ? 'active' : ''; ?>" href="<?php echo WP_DXP_ADMIN_CATEGORIES_INDEX_URL; ?>"> <?php esc_html_e( 'Categories', 'wp-dxp' ); ?></a>
                    </li>
                </ul>
            </nav>
        </header>
    </div>
</div>

<?php
$this->displayFlashMessages(); ?>