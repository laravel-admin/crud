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
        $parent = $this->getModelInstance($id);
        $parent_name = $parent->name;
        $model = $parent->translateOrNew($translation);
        $foreign_key = snake_case(class_basename($this->model))."_id";

        //var_dump(class_basename($this->model));
        //var_dump($foreign_key);

        $model->$foreign_key = $id;

        //	Get all the fields wich will be shown in the edit form
        $fields = new RenderDetail($this->getFieldsForEdit());

        //	Render the view
        return view('crud::templates.translation', $this->parseViewData(compact('parent_name', 'model', 'foreign_key', 'fields', 'translation')));
    }


    public function update(Request $request, $id, $translation)
    {
        //	Validate the request with the specified validation rules and messages
        $this->validate($request, $this->getValidationRulesOnUpdate(), $this->getValidationMessagesOnUpdate());

        //	Get the model instance
        $model = $this->getModelInstance($id);

        $payload = $this->getPayloadOnUpdate($request->all());

        $model->translateOrNew($translation)->fill($payload);
        $model->save();

        $this->flash('The changes has been saved');

        return back();
    }
}
