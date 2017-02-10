<?php

namespace LaravelAdmin\Crud\Traits;


/**
 * Use this trait in a admin controller
 */
trait Crud
{

	/**
	 * Get the model based on the class wich is defined in the controller
	 * An example of a call is $this->model('find', 1) is the same as User::find(1)
	 * @return Builder
	 */

	protected function model()
	{
		//	Validate the given model name
		$this->validateModel();

		//	Get all arguments given to this method
		$args 	= 	func_get_args();

		//	The first argument is the method name, who will be called statically
		$method = 	array_shift($args);

		if (!$method) return new $this->model;

		//	Execute the class method with the given arguments
		return call_user_func_array($this->model . '::' . $method, $args);
	}

	/**
	 * Validate the model name which is specified in the controller
	 * @return void
	 */
	protected function validateModel()
	{
		//	Check if the modelname is defined
		//	If not, throw an exception
		if (empty($this->model))
			throw new \RuntimeException('No model defined in CRUD controller');

		//	Check if the class exists
		//	If not, throw an exception
		if (!class_exists($this->model))
			throw new \RuntimeException('Model with name '.$this->model.' not found');

		//	TODO: Check if the class is a valid Eloquent Model
	}

	/**
	 * Get an instance of a model by the given id
	 * @param  int $id
	 * @return Collection
	 */

	protected function getModelInstance($id)
	{
		return $this->instance = $this->model('findOrFail', $id);
	}

	/**
	 * Get the default validation rules when storing a new model
	 * This method can be overridden by the controller
	 * @return array
	 */
	protected function getValidationRulesOnStore()
	{
		return $this->getValidationRulesOnUpdate();
	}

	/**
	 * Get the default validation rules when storing a new model
	 * This method can be overridden by the controller
	 * @return array
	 */
	protected function getValidationMessagesOnStore()
	{
		return $this->getValidationRulesOnUpdate();
	}

	/**
	 * Get all valid keys of post data what will be saved in the database
	 * @return array
	 */
	protected function getPayloadOnStore(array $payload)
	{
		return $this->getPayloadOnUpdate($payload);
	}

	protected function getPayloadOnStoreDefaults(array $payload)
	{
		$return = [];

		foreach ($this->getFieldsForCreate() as $item)
		{
			if (isset($payload[$item['id']])) $return[$item['id']] = $payload[$item['id']];
		}

		return $return;
	}

	/**
	 * Get all valid keys of post data what will be updated in the database
	 * @return array
	 */

	protected function getPayloadOnUpdate(array $payload)
 	{
 		return $this->getPayloadOnUpdateDefaults($payload);
 	}

 	protected function getPayloadOnUpdateDefaults(array $payload)
 	{
 		$return = [];
 		foreach ($this->getFieldsForEdit() as $item)
 		{
 			if (array_key_exists($item['id'], $payload)) $return[$item['id']] = $payload[$item['id']];
 		}

		return $return;
 	}


	/**
	 * Get the default validation rules when storing a new model
	 * @return array
	 */
	protected function getValidationRulesOnUpdate()
	{
		return [
			'title' => 'required|string',
		];
	}

	/**
	 * Get the default validation rules when storing a new model
	 * @return array
	 */
	protected function getValidationMessagesOnUpdate()
	{
		return [];
	}

	/**
	 * Get all fields for the create form
	 * @return array
	 */
	protected function getFieldsForCreate()
	{
		return $this->getFieldsForEdit();
	}


	/**
	 * Get all fields for the edit form
	 * @return array
	 */
	protected function getFieldsForEdit()
	{
		return [];
	}

	/**
	 * Get all fields the list table
	 * @return array
	 */

	protected function getFieldsForList()
	{
		//	Defaults: Title and Created date
		return [
			'title'	=> ['label' => 'Title', 'formatter'=>'title'],

			//	Added a closure to format the default value
			'created' => ['label' => 'Created', 'formatter' => function($model)
			{
				return $model->created_at->format('Y-m-d');
			}],
		];
	}

	/**
	 * The data to transfer default to all views
	 * @param  $data
	 * @return array
	 */
	protected function parseViewData(array $data = [])
	{
		$data['singular_name'] = $this->singular_name;
		$data['plural_name']   = $this->plural_name;
		$data['route']         = $this->getRouteName();
		$data['parent_route']  = $this->getRouteName(2);
		$data['layout']        = $this->layout();
		$data['submenu'] 	   = $this->getSubmenu();

		//	TODO: What else

		return $data;
	}

	/**
	 * Get submenu
	 *
	 * @return
	 */
	protected function getSubmenu()
	{
		// Check if we have different submenu
		if(property_exists($this, 'submenu')) {
			$action = $this->submenu;
		} else {
			$current_route = \Route::currentRouteName();

			//	Make an array based on the dots in the route
			$route_parts = explode(".", $current_route);

			//	Remove the last item of the array (the action)
			$action = array_pop($route_parts);
		}

		if ($action == "index" && method_exists($this, 'getSubmenuForList')) return $this->getSubmenuForList();
		if ($action == "create" && method_exists($this, 'getSubmenuForCreate')) return $this->getSubmenuForCreate();
		if ( ($action == "edit" || $action == 'show') && method_exists($this, 'getSubmenuForEdit')) return $this->getSubmenuForEdit();

		return [];
	}


	protected function getRouteName($pop=1)
	{
		//	If custom route defined in controller, use this
		if (property_exists($this, 'route') && !empty($this->route)) return trim($this->route,'.').'.';

		//	Get the current route
		$current_route = \Route::currentRouteName();

		//	Make an array based on the dots in the route
		return collect(explode(".", $current_route))
				->reverse()
				->slice($pop)
				->reverse()
				->implode('.').'.';
	}

	/**
	 * A helper for calling a route only based on the crud action
	 * @param  string $action
	 * @param  $params
	 * @return Route
	 */
	protected function route($action, $params = null)
	{
		return $params ? route($this->getRouteName().$action, $params) : route($this->getRouteName().$action);
	}


	/**
	 * A helper for redirecting directly to a route only based on the crud action
	 * @param  string $action
 	 * @param  $params
	 * @return Response
	 */

	protected function redirect($action, $params = null)
	{
		return redirect($this->route($action, $params));
	}

	/**
	 * Determine the master layout file
	 * @return string
	 */

	protected function layout()
	{
		//	If custom layout defined in controller, use this
		if (property_exists($this, 'layout') && !empty($this->layout)) return $this->layout;

		if (class_exists('\LaravelAdmin\Base\BaseServiceProvider')) return 'admin::master';

		return 'layouts.admin';
	}

	/**
	 * Send flash message if installed
	 * @return [type] [description]
	 */

	protected function flash($message, $type="success")
	{
		if (!function_exists('flash')) return;

		flash($message, $type);
	}

}
