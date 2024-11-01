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
            <div class="row">
                <div class="col-12">
                    <a class="back d-inline-block mb-3" href="<?php echo WP_DXP_ADMIN_RULES_INDEX_URL; ?>">
                        <i class="bi bi-arrow-left"></i>
                        <?php esc_html_e( 'Back', 'wp-dxp' ); ?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <?php
                    if (!$rule->is_usable) :
                        ?>
                        <div class="alert alert-danger">
                            <p><?php esc_html_e( 'This rule is not currently usable for the following reasons:', 'wp-dxp' ); ?></p>
                            <ul>
                                <li>
                                <?php
                                echo implode( '</li><li>', array_map( 'esc_html', $rule->getConditionDependencyIssues() ) );
                                ?>
                                </li>
                            </ul>
                        </div>
                    <?php
                    endif; ?>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row mb-lg-4">
                <div class="col-10 d-inline-flex align-items-center justify-content-between">
                    <h2 class="h4 section-title mb-2"><?php esc_html_e( 'View / edit rule', 'wp-dxp' ); ?></h2>
                </div>
                <div class="col-2 d-inline-flex align-items-center justify-content-end">
                    <?php if ($rule->can_duplicate) : ?>
                        <a class="d-inline-block mr-5 contextual-link" href="<?php echo $rule->duplicate_url; ?>"><?php esc_html_e( 'Duplicate', 'wp-dxp' ); ?></a>
                    <?php else : ?>
                        <span class="d-inline-block mr-5 contextual-link text-muted"><?php esc_html_e( 'Duplicate', 'wp-dxp' ); ?></span>
                    <?php endif; ?>

                    <?php if ($rule->can_delete) : ?>
                        <a class="d-inline-block contextual-link wp-dxp-delete-rule" href="<?php echo $rule->delete_url; ?>" data-toggle="modal" data-target="#deleteRuleModal"><?php esc_html_e( 'Delete', 'wp-dxp' ); ?></a>
                    <?php else : ?>
                        <span class="d-inline-block contextual-link text-muted"><?php esc_html_e( 'Delete', 'wp-dxp' ); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$rule->can_edit) : ?>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-primary" role="alert">
                            <i class="bi bi-info-circle"></i>
                            <?php esc_html_e( 'Standard rules cannot be edited, however you can duplicate this rule and make your own changes.', 'wp-dxp' ); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>



    <section class="section">
        <div class="container-fluid">
            <div class="row mb-lg-2">
                <div class="col-12 col-lg-10">

                    <form action="<?php echo $rule->edit_url; ?>" method="post" accept-charset="UTF-8" id="wp-dxp-rule-form">
                        <?php
                        include_once('_form.php'); ?>
                    </form>

                </div>
            </div>
        </div>
    </section>


    <section class="section mt-5 pt-4">
        <div class="container-fluid">
            <div class="row mb-lg-2">
                <div class="col-12 col-lg-10">
                    <h2 class="h4 section-subtitle mb-4"><?php esc_html_e( 'Current usage', 'wp-dxp' ); ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-10">

                    <table class="datatable_rules table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Page', 'wp-dxp' ); ?></th>
                                <th><?php esc_html_e( 'Block', 'wp-dxp' ); ?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                        if (count($rule->usage_blocks)) :
                            foreach ($rule->usage_blocks as $i => $block) : ?>
                            <tr class="<?php echo $i % 2 ? 'alternate' : ''; ?>">
                                <td><?php echo esc_html( $block->post_title ); ?></td>
                                <td><?php echo esc_html( $block->name ); ?></td>
                                <td class="d-flex justify-content-end">
                                    <a class="table-link mx-1" href="<?php echo get_edit_post_link( $block->post_id ); ?>"><?php esc_html_e( 'Edit', 'wp-dxp' ); ?></a>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                        else : ?>
                            <tr>
                                <td class="column-columnname" colspan="3"><?php esc_html_e( 'There are no blocks using this rule', 'wp-dxp' ); ?></td>
                            </tr>
                        <?php
                        endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="deleteRuleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="deleteRuleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteRuleModalLabel"><?php esc_html_e( 'Delete rule?', 'wp-dxp' ); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'wp-dxp' ); ?>">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <p><?php esc_html_e( 'Are you sure you want to delete this rule?', 'wp-dxp' ); ?><br><br></p>

            <a class="btn btn-delete" href=""><?php esc_html_e( 'Delete', 'wp-dxp' ); ?></a>
            <button type="button" class="btn alt-bg btn-modal" data-dismiss="modal"><?php esc_html_e( 'Cancel', 'wp-dxp' ); ?></button>
          </div>
        </div>
      </div>
    </div>

	<?php
	require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/footer.php';
