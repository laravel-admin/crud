<?php

namespace LaravelAdmin\Crud\Traits;

use LaravelAdmin\Crud\Fields\RenderList;
use LaravelAdmin\Crud\Fields\RenderDetail;
use Illuminate\Http\Request;


/**
 * Use this trait in a admin controller
 */
trait CrudController
{
	/**
	 * List all records of the model
	 * @return Illuminate\Http\Response
	 */
	public function index()
	{

		//	TODO: Extend this method for searching, filtering and sorting

		//	Get all records of the model
		$records = $this->model('paginate', 100);

		//	Retrieve the fields which will be used in records table
		$fields = new RenderList($this->getFieldsForList());

		//	Load the view
		return view('crud::templates.index', $this->parseViewData(compact('records','fields')));
	}

	/**
	 * Generate the form to create a new instance of the model
	 * @return Illuminate\Http\Response
	 */
	public function create()
	{
	    // Create new instance of the model
	    $model = $this->model();

        //	Get all the fields wich will be shown in the edit form
        $fields = new RenderDetail($this->getFieldsForCreate());

        // Render the view
        return view('crud::templates.create',  $this->parseViewData(compact('model','fields')));
	}

	/**
	 * Store the new instance to the database
	 * @param  Request $request
 	* @return Illuminate\Http\Response
	*/

	public function store(Request $request)
	{
		//	Validate the request by getting the specified validation rules and messages
		$this->validate($request, $this->getValidationRulesOnStore(), $this->getValidationMessagesOnStore());

		$payload = $this->getPayloadOnStore($request->all());

		//	Create a new instance of the model
		$model = $this->model('create', $payload);

		$this->flash('The record has been created');

		//	Redirect to the edit form
		return $this->redirect('edit', $model->id);
	}

	/**
	 * Show the edit form to update the model
	 * @param  int $id The ID of the model
	 * @return Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//	Get the model instance
		$model = $this->getModelInstance($id);

		//	Get all the fields wich will be shown in the edit form
		$fields = new RenderDetail($this->getFieldsForEdit());

		//	Render the view
		return view('crud::templates.edit', $this->parseViewData(compact('model','fields')));
	}

	/**
	 * Update the model instance into the database
	 * @param  Request $request
	 * @param  int  $id
	 * @return Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//	Validate the request with the specified validation rules and messages
		$this->validate($request, $this->getValidationRulesOnUpdate(), $this->getValidationMessagesOnUpdate());

		//	Get the instance of the model
		$model = $this->getModelInstance($id);

		$payload = $this->getPayloadOnUpdate($request->all());

		//	Update the model in the database
		$model->update($payload);

		$this->flash('The changes has been saved');

		//	Return to the edit form
		return back();
	}

	/**
	 * Delete the model instance
	 * @param  int $id
	 * @return Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//	Get the model instance
		$model = $this->getModelInstance($id);

		// 	Delete the instance
		$model->delete();

		$this->flash('The record has been removed');

		//	Return to the index page
		return $this->redirect("index");
	}


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
		return $this->model('findOrFail', $id);
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
 			if (isset($payload[$item['id']])) $return[$item['id']] = $payload[$item['id']];
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
		$data['layout']        = $this->layout();
		$data['submenu'] 	   = $this->getSubmenu();

		//	TODO: What else

		return $data;
	}


	protected function getSubmenu()
	{
		$current_route = \Route::currentRouteName();

		//	Make an array based on the dots in the route
		$route_parts = explode(".", $current_route);

		//	Remove the last item of the array (the action)
		$action = array_pop($route_parts);

		if ($action == "index") return $this->getSubmenuForList();
		if ($action == "create") return $this->getSubmenuForCreate();

		return $this->getSubmenuForEdit();
	}


	protected function getSubmenuForList()
	{
		return [];
	}

	protected function getSubmenuForCreate()
	{
		return [];
	}

	protected function getSubmenuForEdit()
	{

	}

	protected function getRouteName()
	{
		//	If custom route defined in controller, use this
		if (property_exists($this, 'route') && !empty($this->route)) return trim($this->route,'.').'.';

		//	Get the current route
		$current_route = \Route::currentRouteName();

		//	Make an array based on the dots in the route
		$route_parts = explode(".", $current_route);

		//	Remove the last item of the array (the action)
		array_pop($route_parts);

		//	Return as string
		return implode(".", $route_parts).'.';
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
