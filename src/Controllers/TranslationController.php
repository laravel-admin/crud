<?php

namespace LaravelAdmin\Crud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelAdmin\Crud\Fields\RenderDetail;
use LaravelAdmin\Crud\Traits\Crud;

class TranslationController extends Controller
{
	use Crud;

	public function show($id, $translation)
	{
		//	Get the model instance
		$model = $this->getModelInstance($id)->translateOrNew($translation);
		$foreign_key = snake_case(class_basename($this->model))."_id";

		$model->$foreign_key = $id;

		//	Get all the fields wich will be shown in the edit form
		$fields = new RenderDetail($this->getFieldsForEdit());

		//	Render the view
		return view('crud::templates.translation', $this->parseViewData(compact('model','fields', 'translation')));
	}


	public function update(Request $request, $id, $translation)
	{
		//	Get the model instance
		$model = $this->getModelInstance($id);

		$payload = $this->getPayloadOnUpdate($request->all());

		$model->translateOrNew($translation)->fill($payload);
		$model->save();

		$this->flash('The changes has been saved');

		return back();
	}

}