<?php
if ($this->getError('form')) : ?>
<div class="alert alert-danger" role="alert">
    <?php
    echo implode( '<br>', array_map( 'esc_html', $this->getError('form') ) ); ?>
</div>
<?php
endif; ?>

<?php echo Wp_Dxp_Form::wpNonceField(WP_DXP_FORM_NONCE_FIELD_ACTION); ?>

<?php echo Wp_Dxp_Form::hidden('id', $category->id); ?>

<div class="row mb-2">
    <div class="col-12 col-lg-6">
        <div class="form-group mb-2">
            <label for="wp_dxp_form[name]"><?php esc_html_e( 'Category Name', 'wp-dxp' ); ?></label>
            <?php
            $attributes = [
                'class="form-control ' . ($this->getError('name') ? 'is-invalid' : '') . '"',
                'required'
            ];
            echo Wp_Dxp_Form::text('name', esc_attr($category->name), implode( ' ', $attributes ) ); ?>
            <?php
            if ($this->getError('name')) : ?>
            <div class="invalid-feedback"><?php echo esc_html( implode( ', ', $this->getError('name') ) ); ?></div>
            <?php
            endif; ?>
        </div>
    </div>
</div>

<div class="mt-4">

    <?php
    if ($category->can_edit) : ?>
        <button type="submit" class="btn"><?php esc_html_e( 'Save Category', 'wp-dxp' ); ?></button>
    <?php endif; ?>
    <button type="button" class="btn alt-bg btn-modal" data-dismiss="modal"><?php esc_html_e( 'Cancel', 'wp-dxp' ); ?></button>

</div>