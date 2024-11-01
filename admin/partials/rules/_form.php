<?php

if ($this->getError('form')) : ?>
<div class="alert alert-danger" role="alert">
    <?php
    echo implode( '<br>', array_map( 'esc_html', $this->getError('form') ) ); ?>
</div>
<?php
endif; ?>

<?php echo Wp_Dxp_Form::wpNonceField(WP_DXP_FORM_NONCE_FIELD_ACTION); ?>

<?php echo Wp_Dxp_Form::hidden('id', $rule->id); ?>

<div class="row mb-4">
    <div class="col-12 col-lg-6">
        <div class="form-group mb-2">
            <label for="wp_dxp_form[name]"><?php esc_html_e( 'Rule Name', 'wp-dxp' ); ?></label>
            <?php echo Wp_Dxp_Form::text('name', esc_attr($rule->name), 'class="form-control ' . ($this->getError('name') ? 'is-invalid' : '') . '" ' . (($rule->type === 'standard') ? 'disabled' : '')); ?>
            <?php
            if ($this->getError('name')) : ?>
            <div class="invalid-feedback"><i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i> <?php echo esc_html( implode(', ', $this->getError('name') ) ); ?></div>
            <?php
            endif; ?>
        </div>
    </div>
</div>


<div class="row mb-4">
    <div class="col-12 col-lg-6">
        <div class="form-group mb-2 category-dropdown">
            <label for="wp_dxp_form[category_id]"><?php esc_html_e( 'Category', 'wp-dxp' ); ?></label>

            <?php if ($rule->type === 'standard') : ?>
                <?php echo Wp_Dxp_Form::select('category_id', [ null => esc_attr__( '-- Select a category --', 'wp-dxp' ) ] + Wp_Dxp_Category::list(), $rule->category_id, 'class="form-control ' . ($this->getError('category_id') ? 'is-invalid' : '') . '" disabled'); ?>
            <?php else : ?>
                <?php echo Wp_Dxp_Form::select('category_id', [ null => esc_attr__( '-- Select a category --', 'wp-dxp' ) ] + Wp_Dxp_Category::list(), $rule->category_id, 'class="chosen-select form-control ' . ($this->getError('category_id') ? 'is-invalid' : '') . '"'); ?>
            <?php endif; ?>

            <?php
                if ($this->getError('category_id')) : ?>
                <div class="invalid-feedback"><i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i> <?php echo esc_html( implode(', ', $this->getError('category_id') ) ); ?></div>
                <?php
                endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-10">
        <div class="form-group mb-2">
            <label><?php esc_html_e( 'Conditions', 'wp-dxp' ); ?></label>
            <div id="conditions-container" class="conditions" data-condition-count="<?php echo (int) count($rule->conditions); ?>">
                <?php
                if ($rule->conditions) :
                    foreach ($rule->conditions as $i => $condition) : ?>

                        <?php
                            $conditionClass = Wp_Dxp_Conditions::getClass($condition->measure);
                            $measureKey = ! is_null( $conditionClass ) ? $conditionClass->measure_key : null;
                            $metaValue = ! is_null( $measureKey ) && ! empty( $condition->meta->$measureKey ) ? $condition->meta->$measureKey : '';
                        ?>

                        <div data-condition="row" class="condition mb-3 mb-sm-3">

                            <?php if ($rule->type === 'standard') : ?>
                                <?php echo Wp_Dxp_Form::select('[conditions][measure][]', [ null => esc_attr__( '-- Select a measure --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::groupedList(), $condition->measure, 'class="conditions-measure" disabled'); ?>

                                <div class="meta-text-field-wrapper field-wrapper-styles meta-value-wrapper">
                                    <?php echo Wp_Dxp_Form::text('[conditions][meta_value][]', $metaValue, 'class="text-field field-meta-value-text" placeholder="' . esc_attr__( '-- Enter cookie name here --', 'wp-dxp' ) . '" autocomplete="off" disabled'); ?>
                                </div>

                                <?php echo Wp_Dxp_Form::select('[conditions][comparator][]', [ null => esc_attr__( '-- Select a comparator --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::getComparatorList($condition->measure), $condition->comparator, 'class="conditions-comparator" disabled'); ?>
                            <?php else : ?>
                                <?php echo Wp_Dxp_Form::select('[conditions][measure][]', [ null => esc_attr__( '-- Select a measure --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::groupedList(), $condition->measure, 'class="conditions-measure chosen-select"'); ?>

                                <div class="meta-text-field-wrapper field-wrapper-styles meta-value-wrapper">
                                    <?php echo Wp_Dxp_Form::text('[conditions][meta_value][]', $metaValue, 'class="text-field field-meta-value-text" placeholder="' . esc_attr__( '-- Enter cookie name here --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                                </div>

                                <?php echo Wp_Dxp_Form::select('[conditions][comparator][]', [ null => esc_attr__( '-- Select a comparator --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::getComparatorList($condition->measure), $condition->comparator, 'class="conditions-comparator chosen-select"'); ?>
                            <?php endif; ?>

                            <?php
                                $comparisonType = ! is_null( $conditionClass ) && method_exists( $conditionClass, 'getComparisonType' ) ? $conditionClass->getComparisonType() : '';
                                switch ( $comparisonType ) {
                                    case 'text':
                            ?>
                                <?php if ($rule->type === 'standard') : ?>
                                    <?php echo Wp_Dxp_Form::select('[conditions][value][]', [ null => esc_attr__( '-- Select a value --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::getComparisonValues($condition->measure), '', 'class="conditions-value" disabled ' . ($condition->comparator == 'any' ? ' multiple' : '')); ?>
                                    <div class="text-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::text('[conditions][value][]', $condition->value, 'class="text-field field-value-text" placeholder="' . esc_attr__( '-- Enter value here --', 'wp-dxp' ) . '" autocomplete="off" disabled'); ?>
                                    </div>
                                    <div class="datepicker-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::datepicker('[conditions][value][]', '', 'class="datepicker-field field-value-datepicker" placeholder="' . esc_attr__( '-- Select a date --', 'wp-dxp' ) . '" autocomplete="off" disabled'); ?>
                                    </div>
                                <?php else : ?>

                                    <div class="meta-text-field-wrapper field-wrapper-styles meta-value-wrapper" style="display: none;" data-dependency_measure="core_query_string" data-dependency_comparator="key_value">
                                        <?php echo Wp_Dxp_Form::text('[conditions][meta_value][]', $metaValue, 'class="text-field field-meta-value-text" placeholder="' . esc_attr__( '-- Enter key here --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                                    </div>

                                    <?php echo Wp_Dxp_Form::select('[conditions][value][]', [ null => esc_attr__( '-- Select a value --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::getComparisonValues($condition->measure), '', 'class="conditions-value chosen-select"' . ($condition->comparator == 'any' ? ' multiple' : '')); ?>
                                    <div class="text-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::text('[conditions][value][]', $condition->value, 'class="text-field field-value-text" placeholder="' . esc_attr__( '-- Enter value here --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                                    </div>
                                    <div class="datepicker-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::datepicker('[conditions][value][]', '', 'class="datepicker-field field-value-datepicker" placeholder="' . esc_attr__( '-- Select a date --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                                    </div>
                                <?php endif; ?>
                            <?php
                                break;
                                case 'datepicker':
                                    if (!empty($condition->value)) :
                                        $formattedDate = date("d/m/Y", strtotime($condition->value));
                                    else :
                                        $formattedDate = '';
                                    endif;
                            ?>
                                <?php if ($rule->type === 'standard') : ?>
                                    <?php echo Wp_Dxp_Form::select('[conditions][value][]', [ null => esc_attr__( '-- Select a value --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::getComparisonValues($condition->measure), '', 'class="conditions-value" disabled ' . ($condition->comparator == 'any' ? ' multiple' : '')); ?>
                                    <div class="text-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::text('[conditions][value][]', '', 'class="text-field field-value-text" placeholder="' . esc_attr__( '-- Enter value here --', 'wp-dxp' ) . '" autocomplete="off" disabled'); ?>
                                    </div>
                                    <div class="datepicker-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::datepicker('[conditions][value][]', $formattedDate, 'class="datepicker-field field-value-datepicker" placeholder="' . esc_attr__( '-- Select a date --', 'wp-dxp' ) . '" autocomplete="off" disabled'); ?>
                                    </div>
                                <?php else : ?>
                                    <?php echo Wp_Dxp_Form::select('[conditions][value][]', [ null => esc_attr__( '-- Select a value --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::getComparisonValues($condition->measure), '', 'class="conditions-value chosen-select"' . ($condition->comparator == 'any' ? ' multiple' : '')); ?>
                                    <div class="text-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::text('[conditions][value][]', '', 'class="text-field field-value-text" placeholder="' . esc_attr__( '-- Enter value here --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                                    </div>
                                    <div class="datepicker-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::datepicker('[conditions][value][]', $formattedDate, 'class="datepicker-field field-value-datepicker" placeholder="' . esc_attr__( '-- Select a date --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                                    </div>
                                <?php endif; ?>
                            <?php
                                break;
                                default:
                            ?>
                                <?php if ($rule->type === 'standard') : ?>
                                    <?php echo Wp_Dxp_Form::select('[conditions][value][]', [ null => esc_attr__( '-- Select a value --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::getComparisonValues($condition->measure), $condition->value, 'class="conditions-value" disabled ' . ($condition->comparator == 'any' ? ' multiple' : '')); ?>
                                    <div class="text-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::text('[conditions][value][]', '', 'class="text-field field-value-text" placeholder="' . esc_attr__( '-- Enter value here --', 'wp-dxp' ) . '" autocomplete="off" disabled'); ?>
                                    </div>
                                    <div class="datepicker-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::datepicker('[conditions][value][]', '', 'class="datepicker-field field-value-datepicker" placeholder="' . esc_attr__( '-- Select a date --', 'wp-dxp' ) . '" autocomplete="off" disabled'); ?>
                                    </div>
                                <?php else : ?>
                                    <?php echo Wp_Dxp_Form::select('[conditions][value][]', [ null => esc_attr__( '-- Select a value --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::getComparisonValues($condition->measure), $condition->value, 'class="conditions-value chosen-select"' . ($condition->comparator == 'any' ? ' multiple' : '')); ?>
                                    <div class="text-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::text('[conditions][value][]', '', 'class="text-field field-value-text" placeholder="' . esc_attr__( '-- Enter value here --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                                    </div>
                                    <div class="datepicker-field-wrapper field-wrapper-styles">
                                        <?php echo Wp_Dxp_Form::datepicker('[conditions][value][]', '', 'class="datepicker-field field-value-datepicker" placeholder="' . esc_attr__( '-- Select a date --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                                    </div>
                                <?php endif; ?>
                            <?php
                                break;
                                }
                            ?>

                            <?php echo Wp_Dxp_Form::hidden('[conditions][raw_value][]', $condition->raw_value, 'class="conditions-raw-value"'); ?>
                            <?php echo Wp_Dxp_Form::hidden('[conditions][comparisonType][]', $comparisonType, 'class="the-comparison-type"'); ?>

                            <?php if ($rule->type !== 'standard') : ?>
                                <div class="button-conditions-wrapper">
                                    <button type="button" class="remove-condition"><img class="" alt="<?php esc_attr_e( 'Remove', 'wp-dxp' ); ?>" src="<?php echo plugins_url('../../img/bin.svg', __FILE__) ?>"></button>
                                </div>
                            <?php endif; ?>

                        </div>
                        <?php
                        if ($this->getError("conditions[{$i}]")) : ?>
                        <div class="form-control is-invalid" style="display: none;"></div>
                        <div class="invalid-feedback"><i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i> <?php echo esc_html( implode( ', ', $this->getError("conditions[{$i}]") ) ); ?></div>
                        <?php
                        endif; ?>

                    <?php
                    endforeach;
                else: ?>
                    <div data-condition-count="0" data-condition="row" class="condition mb-3 mb-sm-3">
                        <?php echo Wp_Dxp_Form::select('[conditions][measure][]', [ null => esc_attr__( '-- Select a measure --', 'wp-dxp' ) ] + Wp_Dxp_Conditions::groupedList(), null, 'class="conditions-measure chosen-select"'); ?>

                        <div class="meta-text-field-wrapper field-wrapper-styles meta-value-wrapper" style="display: none;">
                            <?php echo Wp_Dxp_Form::text('[conditions][meta_value][]', '', 'class="text-field field-meta-value-text" placeholder="' . esc_attr__( '-- Enter cookie name here --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                        </div>

                        <?php echo Wp_Dxp_Form::select('[conditions][comparator][]', [ null => esc_attr__( '-- Select a comparator --', 'wp-dxp' ) ], null, 'class="conditions-comparator chosen-select"'); ?>

                        <?php echo Wp_Dxp_Form::select('[conditions][value][]', [ null => esc_attr__( '-- Select a value --', 'wp-dxp' ) ], null, 'class="conditions-value chosen-select field-value-select"'); ?>

                        <div class="meta-text-field-wrapper field-wrapper-styles meta-value-wrapper" style="display: none;" data-dependency_measure="core_query_string" data-dependency_comparator="key_value">
		                    <?php echo Wp_Dxp_Form::text('[conditions][meta_value][]', '', 'class="text-field field-meta-value-text" placeholder="' . esc_attr__( '-- Enter key here --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                        </div>

                        <div class="text-field-wrapper field-wrapper-styles" style="display: none;">
	                        <?php echo Wp_Dxp_Form::text('[conditions][value][]', '', 'class="text-field field-value-text" placeholder="' . esc_attr__( '-- Enter value here --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                        </div>

                        <div class="datepicker-field-wrapper field-wrapper-styles" style="display: none;">
                            <?php echo Wp_Dxp_Form::datepicker('[conditions][value][]', '', 'class="datepicker-field field-value-datepicker" placeholder="' . esc_attr__( '-- Select a date --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                        </div>

                        <?php echo Wp_Dxp_Form::hidden('[conditions][raw_value][]', '', 'class="conditions-raw-value"'); ?>
                        <?php echo Wp_Dxp_Form::hidden('[conditions][comparisonType][]', 'select', 'class="the-comparison-type"'); ?>

                        <div class="button-conditions-wrapper">
                            <button type="button" class="remove-condition"><img class="" alt="<?php esc_attr_e( 'Remove', 'wp-dxp' ); ?>" src="<?php echo plugins_url('../../img/bin.svg', __FILE__) ?>"></button>
                        </div>
                    </div>
                    <?php
                    if ($this->getError('conditions[0]')) : ?>
                    <div class="form-control is-invalid" style="display: none;"></div>
                    <div class="invalid-feedback"><?php echo esc_html( implode( ', ', $this->getError('conditions[0]') ) ); ?></div>
                    <?php
                    endif; ?>

                <?php
                endif; ?>

            </div>
            <div id="condition-template" hidden>
                    <div data-condition="added" class="condition mb-3 mb-sm-3">

                        <?php echo Wp_Dxp_Form::select('[conditions][measure][]', [null => '-- Select a measure --'] + Wp_Dxp_Conditions::groupedList(), null, 'class="conditions-measure" disabled="disabled"'); ?>

                        <div class="meta-text-field-wrapper field-wrapper-styles meta-value-wrapper" style="display: none;">
                            <?php echo Wp_Dxp_Form::text('[conditions][meta_value][]', '', 'class="text-field field-meta-value-text" placeholder="' . esc_attr__( '-- Enter cookie name here --', 'wp-dxp' ) . '" autocomplete="off" disabled="disabled"'); ?>
                        </div>

                        <?php echo Wp_Dxp_Form::select('[conditions][comparator][]', [null => '-- Select a comparator --'], null, 'class="conditions-comparator" disabled="disabled"'); ?>

                        <?php echo Wp_Dxp_Form::select('[conditions][value][]', [ null => esc_attr__( '-- Select a value --', 'wp-dxp' ) ], null, 'class="conditions-value" disabled="disabled"'); ?>

                        <div class="meta-text-field-wrapper field-wrapper-styles meta-value-wrapper" style="display: none;" data-dependency_measure="core_query_string" data-dependency_comparator="key_value">
		                    <?php echo Wp_Dxp_Form::text('[conditions][meta_value][]', '', 'class="text-field field-meta-value-text" placeholder="' . esc_attr__( '-- Enter key here --', 'wp-dxp' ) . '" autocomplete="off" disabled="disabled"'); ?>
                        </div>

                        <div class="text-field-wrapper field-wrapper-styles" style="display: none;">
                            <?php echo Wp_Dxp_Form::text('[conditions][value][]', '', 'class="text-field field-value-text" placeholder="-- Enter value here --" autocomplete="off"'); ?>
                        </div>
                        <div class="datepicker-field-wrapper field-wrapper-styles" style="display: none;">
                            <?php echo Wp_Dxp_Form::datepicker('[conditions][value][]', '', 'class="datepicker-field field-value-datepicker" placeholder="' . esc_attr__( '-- Select a date --', 'wp-dxp' ) . '" autocomplete="off"'); ?>
                        </div>

                        <?php echo Wp_Dxp_Form::hidden('[conditions][raw_value][]', '', 'class="conditions-raw-value" disabled="disabled"'); ?>
                        <?php echo Wp_Dxp_Form::hidden('[conditions][comparisonType][]', '', 'class="the-comparison-type" disabled="disabled"'); ?>

                        <div class="button-conditions-wrapper">
                            <button type="button" class="remove-condition"><img class="" alt="<?php esc_attr_e( 'Remove', 'wp-dxp' ); ?>" src="<?php echo plugins_url('../../img/bin.svg', __FILE__) ?>"></button>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    var wpDxpConditions = <?php echo Wp_Dxp_Conditions::toJson(); ?>;
                </script>
        </div>
    </div>
</div>

<?php if ($rule->type !== 'standard') : ?>
    <div class="mb-3">
        <button type="button" class="add-condition">&plus; <?php esc_html_e( 'Add', 'wp-dxp' ); ?></button>
    </div>
<?php endif; ?>

<div class="mt-4" id="form-actions">
    <?php
    if ($rule->can_edit && 0 !== (int) $rule->id ) :
        ?>
        <button type="submit" class="btn"><?php esc_html_e( 'Save Changes', 'wp-dxp' ); ?></button>
        <button type="reset" class="btn alt-bg"><?php esc_html_e( 'Reset', 'wp-dxp' ); ?></button>
        <?php
    elseif ($rule->can_edit) :
        ?>
        <button type="submit" class="btn"><?php esc_html_e( 'Create rule', 'wp-dxp' ); ?></button>
        <a href="<?php echo WP_DXP_ADMIN_DASHBOARD_INDEX_URL; ?>" class="btn alt-bg"><?php esc_html_e( 'Cancel', 'wp-dxp' ); ?></a>
        <?php
    endif; ?>
</div>


<script type="text/javascript">
(function( $ ) {
    'use strict';

    $(".chosen-select").chosen();

    $( ".the-comparison-type" ).each(function( i ) {
        showCorrectComparisonTypeField(this);
    });

    $( ".conditions-measure" ).each(function( i ) {
        showMeasureKeyField(this);
    });

    $(window).load(function() {
        const ruleForm  = $('#wp-dxp-rule-form');
        const formActions = $('#wp-dxp-rule-form #form-actions');
		const consContainer = document.querySelector('#conditions-container');
        formActions.hide();
        ruleForm.on( 'change', function() {
            formActions.show();
			var count = consContainer.querySelectorAll('.condition:not([data-condition=deleted]').length;
			consContainer.setAttribute( 'data-condition-count', count );
        } );
        ruleForm.on( 'input', function() {
            formActions.show();
        } );
		ruleForm.on( 'submit', function() {
			// Remove all 'deleted' inputs so not to affect server processing.
			var deletedCons = document.querySelectorAll( 'div[data-condition="deleted"]' );
			deletedCons.forEach(row => {
				row.parentNode.removeChild(row);
			});
		} );
        ruleForm.on( 'reset', function() {
			// Revert all 'deleted' rows.
			var deletedCons = document.querySelectorAll( 'div[data-condition="deleted"]' );
			deletedCons.forEach(row => {
				row.dataset.condition = row.dataset.prevCondition;
				row.hidden = false;
			});
			// Remove all 'added' rows.
			var addedCons = document.querySelectorAll( 'div[data-condition="added"]' );
			addedCons.forEach(row => {
				row.parentNode.removeChild(row);
			});
			var count = consContainer.querySelectorAll('.condition:not([data-condition=deleted]').length;
			consContainer.setAttribute( 'data-condition-count', count );
            formActions.hide();
        } );
    } );

    if (typeof wpDxpConditions !== 'undefined') {
        var $conditionTemplate = $('#condition-template .condition');
        var $comparatorEl;
        var $valueEl;

        $(document).on('change', '.conditions-measure', function() {
            wp_dxp_measure_set_related_fields(this);
        });

        $(document).on('change', '.conditions-comparator', function() {
            wp_dxp_set_value_field_type(this);
            wp_dxp_comparator_set_related_fields(this);
        });

        $(document).on('change', '.conditions-value', function() {
            wp_dxp_set_raw_value_field_value(this);
        });

        $(document).on('click', '.remove-condition', function(e) {
            var parent = e.target.closest("[data-condition]");
			// Mark it was 'deleted'.
			parent.dataset.prevCondition = parent.dataset.condition;
			parent.dataset.condition = 'deleted';
			parent.hidden = true;
			$('#wp-dxp-rule-form').trigger('change'); // Trigger jquery compat event
        });

        $(document).on('keyup blur', '.field-value-text', function() {
            var $rawValuesEl = $(this).parents('.condition').find('.conditions-raw-value');

            $rawValuesEl.val($(this).val());
        });

        $(document).on('focus',".datepicker-field", function() {
            $(this).datepicker({
                dateFormat: 'dd/mm/yy',
                altField: $(this).parents('.condition').find('.conditions-raw-value'),
                altFormat: 'yy-mm-dd'
            });
        });

        $(document).on('click', '.add-condition', function(e) {
            var $template = $conditionTemplate.clone();

            $template.children('select, input').prop("disabled", false);

            $template.find('.field-meta-value-text').prop("disabled", false);

            var value = $template.children('.conditions-value').first().val();
            $template.children('.conditions-raw-value').val(value);

            $('#conditions-container').append($template);

            $template.children('select').chosen();
        });
    }

    function wp_dxp_set_value_field_type(el, measure) {

        var $parent = $(el).parent();
        var comparator = $(el).val();
        var $textInput = $parent.find('.field-value-text');

        $textInput.prop("disabled", false);

        if (typeof wpDxpConditions[measure] !== 'undefined') {

            if (wpDxpConditions[measure].comparisonType === 'select') {

                var $valueEl = $parent.children('.conditions-value');
                $valueEl.prop("multiple", (comparator == 'any') ? "multiple" : "");
                $valueEl.chosen('destroy');
                $valueEl.chosen();
            }
        } else {
            if (comparator == 'any_value' || comparator == 'no_value') {
                $textInput.prop("disabled", true);
            }
        }
    }

    function wp_dxp_measure_set_related_fields(el) {

        var $parent = $(el).parent();
        var measure = $(el).val();
        var $rawValuesEl = $parent.find('.conditions-raw-value');

        var $comparatorEl = $parent.children('.conditions-comparator');
        var $valueEl = $parent.children('.conditions-value');
        var $comparisonTypeEl = $parent.find('.the-comparison-type');

        var $selectFieldContainer = $parent.find('.chosen-container-single:last');
        var $textFieldContainer = $parent.find('.text-field-wrapper');
        var $datePickerFieldContainer = $parent.find('.datepicker-field-wrapper');
        var $metaTextFieldContainer = $parent.find('.meta-text-field-wrapper');

        $comparisonTypeEl.val(wpDxpConditions[measure].comparisonType);

        // reset input values
        $textFieldContainer.children('.field-value-text').val('');
        $datePickerFieldContainer.children('.field-value-datepicker').val('');
        $metaTextFieldContainer.children('.field-meta-value-text').val('');

        $comparatorEl.find('option').not(':first').remove();
        $valueEl.find('option').not(':first').remove();

        if (typeof wpDxpConditions[measure] !== 'undefined') {
            $.each(wpDxpConditions[measure].comparators, function(key,value) {
                $comparatorEl.append($("<option></option>")
                    .attr("value", key).text(value));
            });

            $.each(wpDxpConditions[measure].comparisonValues, function(key,value) {
                $valueEl.append($("<option></option>")
                    .attr("value", key).text(value));
            });
        }

        $comparatorEl.chosen('destroy');
        $comparatorEl.chosen();
        wp_dxp_set_value_field_type(el, measure);
        wp_dxp_set_raw_value_field_value(el);

        // show/hide field types dependant on the measure
        showMeasureKeyField(el);
        showCorrectComparisonTypeField(el);
    }

    function showMeasureKeyField(el) {
        var $parent = $(el).parent();
        var measure = $(el).val();
        var comparator = $parent.children('.conditions-comparator').val();
        var $textInput = $parent.find('.field-value-text');
        var $metaTextFieldContainer = $parent.find('.meta-text-field-wrapper');

        if (measure === 'core_cookie') {
            $metaTextFieldContainer.show();
        } else {
            $metaTextFieldContainer.hide();
        }

        if (comparator == 'any_value' || comparator == 'no_value') {
            $textInput.prop("disabled", true);
        }

        wp_dxp_comparator_set_related_fields($parent.children('.conditions-comparator'));
    }

    function showCorrectComparisonTypeField(el) {
        var $parent = $(el).parent();

        var $comparisonTypeValue = $parent.find('.the-comparison-type').val();

        var $selectFieldContainer = $parent.find('.chosen-container-single:last');
        var $textFieldContainer = $parent.find('.text-field-wrapper');
        var $datePickerFieldContainer = $parent.find('.datepicker-field-wrapper');

        if (screen.width < 600) {
            $selectFieldContainer = $parent.find('.conditions-value');
        }

        switch($comparisonTypeValue) {
            case 'text':
                $selectFieldContainer.hide();
                $textFieldContainer.show();
                $datePickerFieldContainer.hide();
            break;
            case 'datepicker':
                $selectFieldContainer.hide();
                $textFieldContainer.hide();
                $datePickerFieldContainer.show();
            break;
            default:
                $selectFieldContainer.show();
                $textFieldContainer.hide();
                $datePickerFieldContainer.hide();
        }
    }

    function wp_dxp_set_raw_value_field_value(el) {
        var $parent = $(el).parent();

        var $valueEl = $parent.children('.conditions-value');
        var $rawValuesEl = $parent.children('.conditions-raw-value').first();
        var value = $valueEl.val();

        if (Array.isArray(value)) {
            $rawValuesEl.val(JSON.stringify(value));
        } else {
            $rawValuesEl.val(value);
        }
    }

    /**
     * Displays related fields based on comparator value
     *
     * @param $comparator comparator element
     */
    function wp_dxp_comparator_set_related_fields($comparator) {
        let $parent = $($comparator).parents('.condition');
        let measure = $parent.find('.conditions-measure').val();
        let comparator = $($comparator).val();

        $parent.find('[data-dependency_measure][data-dependency_comparator]').hide()
            .find('[name]').prop('disabled', 'disabled');

        let dependencyFields = $parent.find('[data-dependency_measure="'+ measure +'"][data-dependency_comparator="'+ comparator +'"]');
        dependencyFields.each(function() {
            let $field = $(this).find('[name]');

            $parent.find('[name="'+ $field.attr('name') +'"]').prop('disabled', true);
            $field.prop('disabled', false);

            $(this).show();
        });
    }
    $(window).on('load', function() {
        $('.conditions-comparator').each(function() {
            wp_dxp_comparator_set_related_fields($(this));
        });
    });

})( jQuery );

</script>
