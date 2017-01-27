<?php

namespace LaravelAdmin\Crud\Controllers;

use App\Http\Controllers\Controller;
use LaravelAdmin\Crud\Fields\RenderList;
use LaravelAdmin\Crud\Fields\RenderDetail;
use Illuminate\Http\Request;
use LaravelAdmin\Crud\Traits\Crud;

abstract class ResourceController extends Controller
{
	use Crud;

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
	public function update(Request $request, $id, $redirect=true)
	{
		//	Validate the request with the specified validation rules and messages
		$this->validate($request, $this->getValidationRulesOnUpdate(), $this->getValidationMessagesOnUpdate());

		//	Get the instance of the model
		$model = $this->getModelInstance($id);

		$payload = $this->getPayloadOnUpdate($request->all());


		//	Update the model in the database
		$model->update($payload);

		if ($redirect)
		{
			$this->flash('The changes has been saved');
			//	Return to the edit form
			return back();
		}

		return $model;
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

}
