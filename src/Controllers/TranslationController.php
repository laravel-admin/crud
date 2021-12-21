<?php

namespace LaravelAdmin\Crud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use LaravelAdmin\Crud\Fields\RenderDetail;
use LaravelAdmin\Crud\Traits\CanBeSecured;
use LaravelAdmin\Crud\Traits\Crud;

class TranslationController extends Controller
{
    use Crud;
    use CanBeSecured;

    public function show($id, $translation)
    {
        $this->checkRole();

        //	Get the model instance
        $parent = $this->getModelInstance($id);
        $select_parent_name = (property_exists($this, 'parent_name')) ? $this->parent_name : 'name';
        $parent_name = $parent->$select_parent_name;
        $model = $parent->translateOrNew($translation);
        $foreign_key = Str::snake(class_basename($this->model)) . '_id';

        $model->$foreign_key = $id;

        //	Get all the fields wich will be shown in the edit form
        $fields = new RenderDetail($this->getFieldsForEdit());

        //	Render the view
        return view('crud::templates.translation', $this->parseViewData(compact('select_parent_name', 'parent_name', 'model', 'foreign_key', 'fields', 'translation')));
    }

    public function update(Request $request, $id, $translation)
    {
        $this->checkRole();

        //	Validate the request with the specified validation rules and messages
        $this->validate($request, $this->getValidationRulesOnUpdate(), $this->getValidationMessagesOnUpdate());

        //	Get the model instance
        $model = $this->getModelInstance($id);

        $payload = $this->getPayloadOnUpdate($request->all());

        // Add user_id to payload
        if (\Schema::hasColumn($this->model()->getTable(), 'updated_by')) {
            $payload['updated_by'] = \Auth::user()->id;
        }

        $model->translateOrNew($translation)->fill($payload);
        $model->save();

        $this->flash('The changes has been saved');

        return back();
    }
}
