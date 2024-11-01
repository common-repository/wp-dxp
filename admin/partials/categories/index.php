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

<section class="section wp-dxp-body-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive-lg dash-actions categories-dash">
                    <div class="button-holder add-button-holder">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCategoryModal"><?php esc_html_e( 'Create Category', 'wp-dxp' ); ?></button>
                    </div>
                    <table class="datatable_categories table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><div class="sorting"><span><?php esc_html_e( 'Category Name', 'wp-dxp' ); ?></span></div></th>
                                <th><div class="sorting"><span><?php esc_html_e( 'Rules', 'wp-dxp' ); ?></span></div></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($categories as $i => $category) : ?>
                            <tr class="<?php echo $i % 2 ? 'alternate' : ''; ?>">
                                <td><?php echo esc_attr($category->name); ?></td>
                                <td><?php echo esc_attr($category->rules_count); ?></td>
                                <td>
                                    <div class="d-flex justify-content-end position-relative">
                                        <div class="contextual-actions-wrapper">
                                            <img class="js-open-contextual-actions" alt="<?php esc_attr_e( 'Actions', 'wp-dxp' ); ?>" src="<?php echo plugins_url('../../img/contextual.svg', __FILE__) ?>">
                                            <div class="contextual-actions-popup">
                                                <div class="contextual-popup-inner">
                                                    <a class="contextual-link wp-dxp-edit-category" href="<?php echo $category->edit_url; ?>" data-toggle="modal" data-target="#editCategoryModal" data-id="<?php echo esc_attr($category->id); ?>" data-name="<?php echo esc_attr($category->name); ?>"><?php echo $category->can_edit ? esc_html__( 'Edit', 'wp-dxp' ) : esc_html__( 'View' , 'wp-dxp' ); ?></a>

                                                    <?php if ($category->rules_count) : ?>
                                                        <a class="contextual-link" href="<?php echo $category->rules_url; ?>"><?php esc_html_e( 'View Rules', 'wp-dxp' ); ?></a>
                                                    <?php endif; ?>

                                                    <?php if ($category->can_delete) : ?>
                                                        <a class="contextual-link wp-dxp-delete-category" href="<?php echo $category->delete_url; ?>" data-toggle="modal" data-target="#deleteCategoryModal"><?php esc_html_e( 'Delete', 'wp-dxp' ); ?></a>
                                                    <?php else : ?>
                                                        <span class="contextual-link text-muted"><?php esc_html_e( 'Delete', 'wp-dxp' ); ?></span>
                                                    <?php endif; ?>
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



<div class="modal fade" id="createCategoryModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createCategoryModalLabel"><?php _e('Add New Category', 'wp-dxp'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="<?php echo WP_DXP_ADMIN_CATEGORIES_CREATE_URL; ?>" method="post" accept-charset="UTF-8" id="wp-dxp-rule-form">
            <?php include('_form.php'); ?>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editCategoryModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel"><?php _e('Edit Category', 'wp-dxp'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="" method="post" accept-charset="UTF-8" id="wp-dxp-rule-form">
            <?php include('_form.php'); ?>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="deleteCategoryModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteCategoryModalLabel"><?php esc_html_e( 'Delete Category', 'wp-dxp' ); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'wp-dxp' ); ?>">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p><?php esc_html_e( 'Are you sure you want to delete this category?', 'wp-dxp' ); ?><br><br></p>

				<a class="btn btn-delete" href=""><?php esc_html_e( 'Delete', 'wp-dxp' ); ?></a>
				<button type="button" class="btn alt-bg btn-modal" data-dismiss="modal"><?php esc_html_e( 'Cancel', 'wp-dxp' ); ?></button>
      </div>
    </div>
  </div>
</div>



<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . '_other/footer.php';
