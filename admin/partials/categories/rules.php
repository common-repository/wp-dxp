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
                <h2 class="h4 section-title mb-2"><?php esc_html_e( 'Rules', 'wp-dxp' ); ?> - <?php echo esc_attr($category->name); ?></h2>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <p class="section-description"><?php esc_html_e( 'Rules that are active will be displayed to a user and are available to use. Click on View/Edit to find out more information about the rule.', 'wp-dxp' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-fluid">
        <div class="row mb-lg-2">
        <div class="col-12">
                <div class="table-responsive-lg">
                    <table class="datatable_rules table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Rule Name', 'wp-dxp' ); ?></th>
                                <th><?php esc_html_e( 'Category', 'wp-dxp' ); ?></th>
                                <th><?php esc_html_e( 'Type', 'wp-dxp' ); ?></th>
                                <th><?php esc_html_e( 'Created By', 'wp-dxp' ); ?></th>
                                <th><?php esc_html_e( 'Used By', 'wp-dxp' ); ?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($category->rules as $i => $rule) :
                            ?>
                            <tr class="<?php echo $i % 2 ? 'alternate' : ''; ?>">
                                <td><?php echo esc_attr($rule->name); ?></td>
                                <td><?php echo esc_attr($rule->category_name); ?></td>
                                <td><?php echo esc_attr($rule->type_friendly); ?></td>
                                <td>
                                    <?php
                                    if ($rule->created_by) : ?>
                                    <a href="/wp-admin/user-edit.php?user_id=<?php echo $rule->created_by; ?>" class="table-link"><?php echo esc_attr($rule->created_by_friendly); ?></a>
                                    <?php
                                    else : ?>
                                    <?php echo esc_attr($rule->created_by_friendly); ?>
                                    <?php
                                    endif; ?>
                                </td>
								<td>
								<?php
								/* translators: 1: %d number of blocks. */
								echo esc_html( sprintf( _n( '%d block', '%d blocks', $rule->usage_blocks_count, 'wp-dxp' ), $rule->usage_blocks_count ) );
								?>
								</td>
                                <td>
                                    <div class="d-flex justify-content-end position-relative">
                                        <div class="contextual-actions-wrapper">
                                            <img class="js-open-contextual-actions" alt="<?php esc_attr_e( 'Actions', 'wp-dxp' ); ?>" src="<?php echo plugins_url('../../img/contextual.svg', __FILE__) ?>">
                                            <div class="contextual-actions-popup">
                                                <div class="contextual-popup-inner">
                                                    <a class="contextual-link" href="<?php echo $rule->edit_url; ?>"><?php echo $rule->can_edit ? esc_html__( 'Edit', 'wp-dxp' ) : esc_html__( 'View', 'wp-dxp' ); ?></a>
                                                    <?php
                                                    if ($rule->can_duplicate) : ?>
                                                    <a class="contextual-link" href="<?php echo $rule->duplicate_url; ?>"><?php esc_html_e( 'Duplicate', 'wp-dxp' ); ?></a>
                                                    <?php
                                                    endif; ?>
                                                    <?php
                                                    if ($rule->can_delete) : ?>
                                                        <a class="contextual-link wp-dxp-delete-rule" href="<?php echo $rule->delete_url; ?>"><?php esc_html_e( 'Delete', 'wp-dxp' ); ?></a>
                                                    <?php
                                                    endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/footer.php';
