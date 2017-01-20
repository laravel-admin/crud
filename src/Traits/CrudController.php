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
		$records = $this->model('all');

		//	Retreive the fields wich will be used in records table
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
		return view('crud::templates.create');
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

		//	Create a new instance of the model
		$model = $this->model('create', $request->only($this->getPayloadOnStore()));

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

		//	Update the model in the database
		$model->update($request->only($this->getPayloadOnUpdate()));

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

		//	The first argument is the method name, who will be called staticly
		$method = 	array_shift($args);

		//	Execute the class method with the given arguments
		return call_user_func_array($this->model.'::'.$method, $args);
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
		return [
			'title' => 'required|string',
		];
	}

	/**
	 * Get the default validation rules when storing a new model
	 * This method can be overridden by the controller
	 * @return array
	 */
	protected function getValidationMessagesOnStore()
	{
		return [];
	}

	/**
	 * Get all valid keys of post data what will be saved in the database
	 * @return array
	 */
	protected function getPayloadOnStore()
	{
		return collect($this->getFieldsForCreate())->map(function($item)
		{
			return $item['id'];
		})->keys()->all();
	}

	/**
	 * Get all valid keys of post data what will be updated in the database
	 * @return array
	 */
	protected function getPayloadOnUpdate()
	{
		return collect($this->getFieldsForEdit())->map(function($item)
		{
			return $item['id'];
		})->keys()->all();
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
		return [];
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
			'title'	=>	['label'=>'Title', 'formatter'=>'title'],

			//	Added a closure to format the default value
			'created' =>	['label'=>'Created', 'formatter'=>function($model)
			{
				return $model->created_at->format('Y-m-d');
			}],
		];
	}

	/**
	 * The data to transfer default to all views
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	protected function parseViewData(array $data=[])
	{
		$data['singular_name'] = $this->singular_name;
		$data['plural_name'] = $this->plural_name;
		$data['route'] = $this->route;

		//	TODO: What else

		return $data;
	}

	/**
	 * A helper for calling a route only based on the crud action
	 * @param  string $action
	 * @param  $params
	 * @return Route
	 */
	protected function route($action, $params = null)
	{
		return $params ? route($this->route.$action, $params) : route($this->route.$action);
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

}
