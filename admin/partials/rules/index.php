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

	<div class="container-fluid pl-0 pr-0 wp-dxp-header-nav">
		<?php
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'personalisation/nav.php';

		$active_count   = count( array_filter( wp_list_pluck( $rules, 'is_usable' ) ) );
		$inactive_count = count( $rules ) - $active_count;
		?>
	</div>

	<section class="section wp-dxp-body-content">
		<div class="container-fluid">
			<div class="row mb-3">
				<div class="col-12 col-xl-8">
					<p class="section-description"><?php esc_html_e( 'Rules that are active will be displayed to a user and are available to use. If a rule is inactive, content related to it will not currently be displayed to a user.', 'wp-dxp' ); ?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="table-responsive-lg dash-actions rules-dash">
						<div class="button-holder add-button-holder">

							<div class="dashboard-status-toggle">
								<input id="status_active" type="radio" name="active_status" value="<?php esc_attr_e( 'Active', 'wp-dxp' ); ?>" checked>
								<label for="status_active">
									<?php
									echo esc_html(
										sprintf(
										/* translators: 1: %d total active rules. */
											__( 'Active (%d)', 'wp-dxp' ),
											number_format_i18n( (int) $active_count )
										)
									);
									?>
								</label>

								<input id="status_inactive" type="radio" name="active_status" value="<?php esc_attr_e( 'Inactive', 'wp-dxp' ); ?>">
								<label for="status_inactive">
									<?php
									echo esc_html(
										sprintf(
										/* translators: 1: %d total inactive rules. */
											__( 'Inactive (%d)', 'wp-dxp' ),
											number_format_i18n( (int) $inactive_count )
										)
									);
									?>
								</label>
							</div>

							<select class="form-control dashboard-category-dropdown">
								<option value=""><?php esc_html_e( 'All categories', 'wp-dxp' ); ?></option>
								<?php foreach ($categories as $i => $category) : ?>
									<option value="<?php echo esc_attr($category->name); ?>"><?php echo esc_attr($category->name); ?></option>
								<?php endforeach; ?>
							</select>
							<a class="btn" href="<?php echo WP_DXP_ADMIN_RULES_CREATE_URL; ?>"><?php esc_html_e( 'Create rule', 'wp-dxp' ); ?></a>
						</div>
						<table class="datatable_dashboard table table-striped table-bordered">
							<thead>
							<tr>
								<th><div class="sorting"><span><?php esc_html_e( 'Rule Name', 'wp-dxp' ); ?></span></div></th>
								<th><div class="sorting"><span><?php esc_html_e( 'Category', 'wp-dxp' ); ?></span></div></th>
								<th><div class="sorting"><span><?php esc_html_e( 'Type', 'wp-dxp' ); ?></span></div></th>
								<th><div class="sorting"><span><?php esc_html_e( 'Created By', 'wp-dxp' ); ?></span></div></th>
								<th><div class="sorting"><span><?php esc_html_e( 'Blocks used in', 'wp-dxp' ); ?></span></div></th>
								<th class="d-none"><?php esc_html_e( 'Status', 'wp-dxp' ); ?></th>
								<th>&nbsp;</th>
							</tr>
							</thead>

							<tbody>
							<?php
							foreach ($rules as $i => $rule) : ?>
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
									<td class="d-none"><?php echo $rule->is_usable ? esc_html__( 'Active', 'wp-dxp' ) : esc_html__( 'Inactive', 'wp-dxp' ); ?></td>
									<td>
										<div class="d-flex justify-content-end position-relative">
											<div class="contextual-actions-wrapper">
												<img class="js-open-contextual-actions" alt="<?php esc_attr_e( 'Actions', 'wp-dxp' ); ?>" src="<?php echo plugins_url('../../img/contextual.svg', __FILE__) ?>">
												<div class="contextual-actions-popup">
													<div class="contextual-popup-inner">
														<a class="contextual-link" href="<?php echo $rule->edit_url; ?>"><?php echo $rule->can_edit ? esc_html__( 'Edit', 'wp-dxp' ) : esc_html__( 'View', 'wp-dxp' ); ?></a>

														<?php if ($rule->can_duplicate) : ?>
															<a class="contextual-link" href="<?php echo $rule->duplicate_url; ?>"><?php esc_html_e( 'Duplicate', 'wp-dxp' ); ?></a>
														<?php endif; ?>

														<?php if ($rule->can_delete) : ?>
															<a class="contextual-link wp-dxp-delete-rule" href="<?php echo $rule->delete_url; ?>" data-toggle="modal" data-target="#deleteRuleModal"><?php esc_html_e( 'Delete', 'wp-dxp' ); ?></a>
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

	<div class="modal fade" id="deleteRuleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="deleteRuleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteRuleModalLabel"><?php esc_html_e( 'Delete Rule', 'wp-dxp' ); ?></h5>
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
