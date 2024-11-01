<?php
class Wp_Dxp_Admin_Rules_Page extends Wp_Dxp_Admin_Base_Page {

	/**
	 * Process - typically used for processing POST data
	 */
	public function process()
	{
		// Double check for user caps before any possible processing of data.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$action = $this->request->get('wp_dxp_action');
		if (!empty($this->request->form())) {
			switch ($action) {
				case 'create':
					return $this->storeAction();
				case 'edit':
					return $this->updateAction();
			}
		}

		switch ($action) {
			case 'delete':
				return $this->deleteAction();
			case 'duplicate':
				return $this->duplicateAction();
		}
	}

	/**
	 * Route to the correct action within the page
	 */
	public function route()
	{
		// Double check for user caps before any possible processing of data.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$action = $this->request->get('wp_dxp_action');

		switch ($action) {
			case 'create':
				return $this->createAction();
			case 'edit':
				return $this->editAction();
		}

		return $this->indexAction();
	}

	/**
	 * Index action
	 */
	public function indexAction()
	{
		$rules = Wp_Dxp_Rule::all();
		$categories = Wp_Dxp_Category::all();

		$is_onboarding = ( '1' === get_option( WP_DXP_ONBOARDING_MESSAGE_OPTION_KEY, '' ) );
		if ( $is_onboarding ) {
			// The option key ensures persistancy.
			$this->addFlashMessage(
				sprintf(
					'<h2>%1$s</h2><p>%2$s</p>',
					esc_html__( 'First time configuration', 'wp-dxp' ),
					esc_html__( 'WP-DXP provides a number of pre-built rules and categories that are ready to use on your site, which you can see below. If you want to add your own, just click on the Create Rule or Create Category button.', 'wp-dxp' ),
				),
				'info',
				'onboarding'
			);
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/rules/index.php';
	}

	/**
	 * Create action - display form
	 */
	public function createAction()
	{
		$rule = new Wp_Dxp_Rule();

		if ($data = $this->request->form()) {
			$rule->populateFromArray($data);
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/rules/create.php';
	}

	/**
	 * Store action - process data from form submitted on "create" page
	 */
	public function storeAction()
	{
		if ($this->request->validateNonce(WP_DXP_FORM_NONCE_FIELD_ACTION)) {

			$data = $this->request->form();
			$this->validate($data);

			if (empty($this->getError())) {
				$this->saveRule($data);

				$this->addFlashMessage( __( 'Rule created', 'wp-dxp' ), "success" );

				wp_redirect( WP_DXP_ADMIN_RULES_INDEX_URL );
				exit;
			}

			return;
		}

		$this->addFlashMessage( __( 'An error occurred, please reload the page', 'wp-dxp' ), "error" );
	}

	/**
	 * Edit action - display form for existing rule
	 */
	public function editAction()
	{
		$id = $this->request->get('id', false);

		$rule = Wp_Dxp_Rule::find($id);

		if (!$rule) {
			$this->showError( __( 'Rule could not be found', 'wp-dxp' ) );
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/rules/edit.php';
	}

	/**
	 * Update action - process data from form submitted on "edit" page
	 */
	public function updateAction()
	{
		if ($this->request->validateNonce(WP_DXP_FORM_NONCE_FIELD_ACTION)) {

			$id = $this->request->form('id', false);

			$rule = Wp_Dxp_Rule::find($id);

			if (!$rule) {
				$this->showError( __( 'Rule could not be found', 'wp-dxp' ) );
			}

			if (!$rule->can_edit) {
				$this->showError( __( 'This rule cannot be edited', 'wp-dxp' ) );
			}

			$data = $this->request->form();

			$this->validate($data, true);

			if (empty($this->getError())) {
				$this->saveRule($data, $id);

				$this->addFlashMessage( __( 'Rule updated', 'wp-dxp' ), "success" );

				// No redirect as re-loading the same edit screen.
			}

			return;
		}

		$this->addFlashMessage( __( 'An error occurred, please reload the page', 'wp-dxp') , "error" );
	}

	/**
	 * Delete action
	 */
	public function deleteAction()
	{
		$id = $this->request->get('id', false);

		$rule = Wp_Dxp_Rule::find($id);

		if (!$rule) {
			$this->showError( __( 'Rule could not be found', 'wp-dxp' ) );
		}

		if (!$rule->can_delete) {
			$this->showError( __( 'Rule cannot be deleted', 'wp-dxp' ) );
		}

		Wp_Dxp_Rule::delete($id);

		$this->addFlashMessage( __( 'Rule deleted', 'wp-dxp' ), "success" );

		wp_redirect( WP_DXP_ADMIN_RULES_INDEX_URL );
		exit;
	}

	/**
	 * Duplicate action
	 */
	public function duplicateAction()
	{
		$id = $this->request->get('id', false);

		$rule = Wp_Dxp_Rule::find($id);

		if (!$rule) {
			$this->showError( __( 'Rule could not be found', 'wp-dxp' ) );
		}

		$duplicateRule = $rule->clone();

		if ( $duplicateRule instanceof Wp_Dxp_Rule) {
			$this->addFlashMessage( __( 'Rule duplicated', 'wp-dxp' ), "success" );

			wp_redirect( $duplicateRule->getEditUrlAttribute() );
		} else {
			$this->addFlashMessage( __( 'Error duplicating rule', 'wp-dxp' ), "warning" );

			wp_redirect( WP_DXP_ADMIN_RULES_INDEX_URL );
		}
		exit;
	}

	/**
	 * Save rule to DB (either create or update)
	 * @param  array $data
	 * @param  integer $id
	 * @return Wp_Dxp_Rule
	 */
	protected function saveRule($data, $id = null)
	{
		if ($id) {
			$rule = Wp_Dxp_Rule::find($id);
		}

		if (empty($rule)) {
			$rule = new Wp_Dxp_Rule();
		}

		$rule->populateFromArray($data);

		// Is new rule
		if (empty($rule->id)) {
			$rule->type = Wp_Dxp_Rule_Types::$CUSTOM;
			$rule->created_by = get_current_user_id();
		}

		$rule->save();

		return $rule;
	}

	/**
	 * Validate data, likely from submitted form
	 * @param  array $data
	 * @param  boolean $existingModel
	 * @return array
	 */
	protected function validate($data, $existingModel = false)
	{
		if (empty($data)) {
			$this->addValidationError( 'form', __( 'Form is not valid', 'wp-dxp' ) );
		} else {

			if ($existingModel) {
				if (empty($data['id'])) {
					$this->addValidationError( 'form', __( 'ID is required to update a rule', 'wp-dxp' ) );
				}
			}

			if (empty($data['name'])) {
				$this->addValidationError( 'name', __('Please enter a name', 'wp-dxp'));
			}

			if ( Wp_Dxp_Rule::check_name( $data['name'], $data['id'] ) ) {
				$this->addValidationError( 'name', __( 'This rule name already exists. Please choose a different name.', 'wp-dxp' ) );
			}

			if (empty($data['category_id'])) {
				$this->addValidationError( 'category_id', __( 'Please select a category', 'wp-dxp' ) );
			}

			if (!empty($data['conditions']['measure'])) {
				foreach ($data['conditions']['measure'] as $i => $measure) {
					if (empty($measure)) {
						$this->addValidationError( 'conditions['.$i.']', __( 'A measure for the condition should be selected', 'wp-dxp' ) );
					}
				}
			}

			if (!empty($data['conditions']['meta_value'])) {
				foreach ($data['conditions']['meta_value'] as $i => $meta_value) {
					$conditionClass = Wp_Dxp_Conditions::getClass($data['conditions']['measure'][$i]);

					if (empty($meta_value) && $conditionClass && ! empty( $conditionClass->measure_key ) ) {
						$this->addValidationError( 'conditions['.$i.']', __( 'The meta value for the condition should not be empty', 'wp-dxp' ) );
					}
				}
			}

			if (!empty($data['conditions']['comparator'])) {
				foreach ($data['conditions']['comparator'] as $i => $comparator) {
					if (empty($comparator)) {
						$this->addValidationError( 'conditions['.$i.']', __( 'A comparator for the condition should be selected', 'wp-dxp' ) );
					}
				}
			}

			if (!empty($data['conditions']['raw_value'])) {
				foreach ($data['conditions']['raw_value'] as $i => $value) {
					$comparitor_value = $data['conditions']['comparator'][$i];

					if (empty($value) && $comparitor_value !== 'any_value' && $comparitor_value !== 'no_value') {
						if ($data['conditions']) {
							$this->addValidationError( 'conditions['.$i.']', __( 'Please select a value', 'wp-dxp' ) );
						}
					}
				}
			}
		}
	}
}
