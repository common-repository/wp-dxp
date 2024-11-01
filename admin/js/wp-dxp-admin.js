(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	$(window).load(function() {
		if ( window.wpDXP ) {
			document.querySelector(`ul#adminmenu a[href=\'${window.wpDXP.kb}\']`).setAttribute("target", "_blank");
		}
		$('#createCategoryModal').on('show.bs.modal', function (event) {
			var modal = $(this);
			modal.find('[name="wp_dxp_form[name]"').attr('value', '');
		})

		$('#editCategoryModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var url = button.attr('href')
			var id = button.attr('data-id')
			var name = button.attr('data-name')

			var modal = $(this);
			modal.find('form').attr('action', url);
			modal.find('[name="wp_dxp_form[id]"').attr('value', id);
			modal.find('[name="wp_dxp_form[name]"').attr('value', name);
		})


		$('#deleteCategoryModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var url = button.attr('href')

			var modal = $(this);
			modal.find('.btn-delete').attr('href', url);
		})


		$('#deleteRuleModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var url = button.attr('href')

			var modal = $(this);
			modal.find('.btn-delete').attr('href', url);
		})
	});

	$(document).on('click', '.wp-dxp-delete-rule', function (event) {
		event.preventDeafult();
		// return confirm("Are you sure you want to delete this rule?");
	});

	$(document).on('click', '.wp-dxp-delete-category', function (event) {
		event.preventDeafult();
		// return confirm("Are you sure you want to delete this category?");
	});


	$(document).ready(function() {
	    var dataTable_dashboard = $('.datatable_dashboard').DataTable({
	    	responsive: true,
			"pageLength": 20,
			"lengthMenu": [10, 20, 50, 100],
			"order": [1, 'asc'],
			"pagingType": 'simple',
			"columnDefs": [
				{ "orderable": false, "targets": 6 }
			],
			"dom": 'ft<"bottom-table"lip>',
			"language": {
				"search":            '',
    			"searchPlaceholder": wpDXPL10n.DTsearchPlaceholder,
				"lengthMenu":        wpDXPL10n.DTlengthMenu,
				"info":              wpDXPL10n.DTinfo,
				"infoEmpty":         wpDXPL10n.DTinfoEmpty,
				"infoFiltered":      '',
				"zeroRecords":       wpDXPL10n.DTzeroRecords,
				"paginate": {
				  "next": `<span class="dashicons dashicons-arrow-right-alt2"></span><span class="screen-reader-text">${wpDXPL10n.DTpaginateNext}</span>`,
				  "previous": `<span class="dashicons dashicons-arrow-left-alt2"></span><span class="screen-reader-text">${wpDXPL10n.DTpaginatePrevious}</span>`
				}
			}
	    }).column(5).search(wpDXPL10n.active,false,false,false).draw();

	    $('.datatable_rules').DataTable({
	    	responsive: true,
			"pageLength": 20,
			"lengthMenu": [10, 20, 50, 100],
			"columnDefs": [
				{ "orderable": false, "targets": 2 }
			],
			"dom": 'ft<"bottom-table"lip>',
			"language": {
				"search":            '',
    			"searchPlaceholder": wpDXPL10n.DTsearchPlaceholder,
				"lengthMenu":        wpDXPL10n.DTlengthMenu,
				"info":              wpDXPL10n.DTinfo,
				"infoEmpty":         wpDXPL10n.DTinfoEmpty,
				"infoFiltered":      '',
				"zeroRecords":       wpDXPL10n.DTzeroRecords,
				"paginate": {
				  "next": `<span class="dashicons dashicons-arrow-right-alt2"></span><span class="screen-reader-text">${wpDXPL10n.DTpaginateNext}</span>`,
				  "previous": `<span class="dashicons dashicons-arrow-left-alt2"></span><span class="screen-reader-text">${wpDXPL10n.DTpaginatePrevious}</span>`
				}
			}
	    });

		$('.datatable_categories').DataTable({
	    	responsive: true,
			"pageLength": 20,
			"lengthMenu": [10, 20, 50, 100],
			"columnDefs": [
				{ "orderable": false, "targets": 2 }
			],
			"dom": 'ft<"bottom-table"lip>',
			"language": {
				"search":            '',
    			"searchPlaceholder": wpDXPL10n.DTsearchPlaceholder,
				"lengthMenu":        wpDXPL10n.DTlengthMenu,
				"info":              wpDXPL10n.DTinfo,
				"infoFiltered":      '',
				"infoEmpty":         wpDXPL10n.DTinfoEmpty,
				"zeroRecords":       wpDXPL10n.DTzeroRecords,
				"paginate": {
				  "next": `<span class="dashicons dashicons-arrow-right-alt2"></span><span class="screen-reader-text">${wpDXPL10n.DTpaginateNext}</span>`,
				  "previous": `<span class="dashicons dashicons-arrow-left-alt2"></span><span class="screen-reader-text">${wpDXPL10n.DTpaginatePrevious}</span>`
				}
			}
	    });

		$(document).on('change', '.dashboard-category-dropdown', function (e) {
			var cats = $(this).val();
			var regex = false;
			if ( Array.isArray(cats) ) {
				regex = true;
				cats = cats.join( '|' );
			}
			dataTable_dashboard.column(1).search(`${cats}`,regex,!regex).draw();
		});

		$(document).on('change', 'input[name="active_status"]', function (e) {
			var status = $(this).val();
			$('.dashboard-status-toggle').val(status);
			dataTable_dashboard.column(5).search(status,false,false,false).draw();
		});

		$(document).on('click', '.datatable_dashboard tbody tr', function () {
			var rowURL = $(this).find('td:last-child .contextual-link:first-child').attr('href');

			if (rowURL) {
				window.location.href = rowURL;
			}
		});

		$(document).on('click', '.js-open-contextual-actions', function (e) {
			e.stopPropagation();

			var $elem = $(this).parent().children('.contextual-actions-popup');

			// decide which contextual options to show / hide
			if ($elem.hasClass('active')) {
				$elem.removeClass('active');
			} else {
				$('.contextual-actions-popup').removeClass('active');
				$elem.addClass('active');
			}
		});

		/**
		 * Settings screen
		 */
		$(document).on('change keyup', '.wp-dxp-input-group--error input', function() {
			$(this).parents('.wp-dxp-input-group--error').removeClass('wp-dxp-input-group--error').find('.wp-dxp-input-group__error').remove();
		});

		$(document).on('submit', '#wp-dxp-newsletter-signup', function(e) {
			e.preventDefault();

			$('.wp-dxp-input-group__error').remove();

			$.ajax({
				url: wpDXP.url,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'dxp_newsletter_signup',
					first_name: jQuery('input[name="first_name"]').val(),
					last_name: jQuery('input[name="last_name"]').val(),
					email_address: jQuery('input[name="email_address"]').val(),
					terms_acceptance: jQuery('input[name="terms_acceptance"]:checked').val(),
					nonce: wpDXP.nonce
				},
				success: function( response ) {
					if ( response.success === true ) {
						response.data.forEach((message, index) => {
							if ( message.type == 'redirect' && message.url ) {
								window.location.replace( message.url );
							}
						});
					}
					else {
						response.data.forEach((message, index) => {
							if ( message.type == 'error' ) {
								if ( message.input != '' ) {
									$('#wp-dxp-newsletter-signup input[name="'+ message.input +'"]').parent()
										.addClass('wp-dxp-input-group--error')
										.append('<span class="wp-dxp-input-group__error">'+ message.message +'</span>');
								}
								else {
									$('#wp-dxp-newsletter-signup')
										.append('<span class="wp-dxp-input-group__error">'+ message.message +'</span>');
								}
							}
						});
					}
				}
			});
		});

		/**
		 * Dismiss Dashboard message
		 */
		$('.wp-dxp-page .wp-dxp-header + .wp-dxp-messages .close').click(function() {
			var type = $(this).data('dismissType');
			if (type){
				$.ajax({
					url: wpDXP.url,
					type: 'POST',
					dataType: 'json',
					data: {
						action: `dxp_dismiss_${type}_message`,
						nonce: wpDXP.nonce
					}
				});
			}
		});
	});

})( jQuery );
