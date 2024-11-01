<?php
class Wp_Dxp_Admin_Categories_Page extends Wp_Dxp_Admin_Base_Page {

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
			case 'rules':
				return $this->viewRulesAction();
		}

		return $this->indexAction();
	}

	/**
	 * Index action
	 */
	public function indexAction()
	{
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

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/categories/index.php';
	}

	/**
	 * Create action - display form
	 */
	public function createAction()
	{
		$category = new Wp_Dxp_Category();

		if ($data = $this->request->form()) {
			$category->populateFromArray($data);
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/categories/create.php';
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
				$this->saveCategory($data);

				$this->addFlashMessage( __( 'Category created', 'wp-dxp' ), "success" );

				wp_redirect(WP_DXP_ADMIN_CATEGORIES_INDEX_URL);
				exit;
			}

			return;
		}

		$this->addFlashMessage( __( 'An error occurred, please reload the page', 'wp-dxp' ), "error" );
	}

	/**
	 * Edit action - display form for existing category
	 */
	public function editAction()
	{
		$id = $this->request->get('id', false);

		$category = Wp_Dxp_Category::find($id);

		if (!$category) {
			$this->showError( __( 'Category could not be found', 'wp-dxp' ) );
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/categories/edit.php';
	}

	/**
	 * Edit action - display form for existing category
	 */
	public function viewRulesAction()
	{
		$id = $this->request->get('id', false);

		$category = Wp_Dxp_Category::find($id);

		if (!$category) {
			$this->showError( __( 'Category could not be found', 'wp-dxp' ) );
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/categories/rules.php';
	}

	/**
	 * Update action - process data from form submitted on "edit" page
	 */
	public function updateAction()
	{
		if ($this->request->validateNonce(WP_DXP_FORM_NONCE_FIELD_ACTION)) {

			$id = $this->request->form('id', false);

			$category = Wp_Dxp_Category::find($id);

			if (!$category) {
				$this->showError( __( 'Category could not be found', 'wp-dxp' ) );
			}

			if (!$category->can_edit) {
				$this->showError( __( 'This category cannot be edited', 'wp-dxp' ) );
			}

			$data = $this->request->form();

			$this->validate($data, true);

			if (empty($this->getError())) {
				$this->saveCategory($data, $id);

				$this->addFlashMessage( __( 'Category updated', 'wp-dxp' ), "success" );

				wp_redirect(WP_DXP_ADMIN_CATEGORIES_INDEX_URL);
				exit;
			}

			return;
		}

		$this->addFlashMessage( __( 'An error occurred, please reload the page', 'wp-dxp' ), "error" );
	}

	/**
	 * Delete action
	 */
	public function deleteAction()
	{
		$id = $this->request->get('id', false);

		$category = Wp_Dxp_Category::find($id);

		if (!$category) {
			$this->showError( __( 'Category could not be found', 'wp-dxp' ) );
		}

		if (!$category->can_delete) {
			$this->showError( __( 'Category cannot be deleted', 'wp-dxp' ) );
		}

		Wp_Dxp_Category::delete($id);

		$this->addFlashMessage( __( 'Category deleted', 'wp-dxp' ), "success" );

		wp_redirect(WP_DXP_ADMIN_CATEGORIES_INDEX_URL);
		exit;
	}

	/**
	 * Save category to DB (either create or update)
	 * @param  array $data
	 * @param  integer $id
	 * @return Wp_Dxp_Category
	 */
	protected function saveCategory($data, $id = null)
	{
		if ($id) {
			$category = Wp_Dxp_Category::find($id);
		}

		if (empty($category)) {
			$category = new Wp_Dxp_Category();
		}

		$category->populateFromArray([
			'name' => $data['name'],
		]);

		$category->save();

		return $category;
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
					$this->addValidationError( 'form', __( 'ID is required to update a category', 'wp-dxp' ) );
				}
			}

			if (empty($data['name'])) {
				$this->addValidationError( 'name', __( 'Name is required', 'wp-dxp' ) );
			}

			if ( Wp_Dxp_Category::check_name( $data['name'], $data['id'] ) ) {
				$this->addValidationError( 'name', __( 'This category name already exists. Please choose a different name.', 'wp-dxp' ) );
			}

		}
	}
}
