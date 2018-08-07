<?php

namespace LaravelAdmin\Crud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelAdmin\Crud\Fields\RenderDetail;
use LaravelAdmin\Crud\Fields\RenderList;
use LaravelAdmin\Crud\Traits\CanBeSecured;
use LaravelAdmin\Crud\Traits\Crud;
use LaravelAdmin\Crud\Traits\Relation;

abstract class RelationController extends Controller
{
    use Crud, Relation, CanBeSecured;

    public function index($id)
    {
        $this->checkRole();

        $model = $this->getModelInstance($id);

        $records = $this->relation('all');
        $definition = $this->definition;
        $checked = $model->$definition->pluck('id');

        //	Retrieve the fields which will be used in records table
        $fields = new RenderList($this->getFieldsForList());

        return view('crud::templates.relation', $this->parseViewData(compact('model', 'records', 'checked', 'fields')));
    }

    public function store(Request $request, $id)
    {
        $this->checkRole();

        $this->validate($request, ['items'=>'array']);

        $model = $this->getModelInstance($id);

        $model->categories()->sync($request->items ?: []);

        $this->flash('The relations are saved');

        return back();
    }
}
