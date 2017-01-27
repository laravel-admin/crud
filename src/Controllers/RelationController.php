<?php

namespace LaravelAdmin\Crud\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelAdmin\Crud\Fields\RenderList;
use LaravelAdmin\Crud\Fields\RenderDetail;
use LaravelAdmin\Crud\Traits\Crud;
use LaravelAdmin\Crud\Traits\Relation;

abstract class RelationController extends Controller
{
    use Crud, Relation;

	public function index($id)
	{
		$model = $this->getModelInstance($id);

		$records = $this->relation('all');
		$definition = $this->definition;
		$checked = $model->$definition->pluck('id');

		//	Retrieve the fields which will be used in records table
		$fields = new RenderList($this->getFieldsForList());

		return view('crud::templates.relation', $this->parseViewData(compact('model','records','checked','fields')));
	}

	public function store(Request $request, $id)
	{
		$this->validate($request, ['items'=>'array']);

		$model = $this->getModelInstance($id);

		$model->categories()->sync($request->items ?: []);

		$this->flash('The relations are saved');

		return back();
	}

	protected function getFieldsForList()
	{
		return [

				[
					'id'		=>	'name',
					'label'		=>	'Name',
					'formatter'	=>	'name',
				],
		];
	}
}
