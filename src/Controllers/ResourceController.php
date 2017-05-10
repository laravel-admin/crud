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
    public function index(Request $request)
    {
        //	Get all records of the model and set default ordering
        $builder = $this->model('orderBy', $request->orderby ?: $this->list_order_by, $request->order ?: 'desc');

        //	Retrieve the fields which will be used in records table
        $fields = new RenderList($this->getFieldsForList());

        // Handle list search
        if ($request->has('s')) {
            $builder->where($this->list_search_on, 'LIKE', '%' . $request->s . '%');
        }

        // Handle list filtering
        if ($request->has('filter') && $request->has('set')) {
            $builder->where($request->filter, $request->set);
        }

        // Move $builder to $records with default paging of 40 p/p
        $records = $builder->paginate(40);

        //	Load the view
        return view('crud::templates.index', $this->parseViewData(compact('records', 'fields')));
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
        return view('crud::templates.create', $this->parseViewData(compact('model', 'fields')));
    }

    /**
     * Store the new instance to the database
     * @param  Request $request
     * @return Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // Check for bulk action
        if ($request->has('action') && $request->has('record')) {
            // Handle bulk action
            return $this->bulk($request);
        }

        //	Validate the request by getting the specified validation rules and messages
        $this->validate($request, $this->getValidationRulesOnStore(), $this->getValidationMessagesOnStore());

        // Get payload from settings
        $payload = $this->getPayloadOnStore($request->all());

        //	Create a new instance of the model
        $model = $this->model('create', $payload);

        // Set flash message
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
        return view('crud::templates.edit', $this->parseViewData(compact('model', 'fields')));
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

        // Get payload from settings
        $payload = $this->getPayloadOnUpdate($request->all());

        //	Update the model in the database
        $model->update($payload);

        // Get existing relations of the model
        $relations = $this->getRelationsOnUpdate($request->all());

        // Update the existing relations in the database
        foreach ($relations as $relation => $value) {
            $model->$relation()->sync($value);
        }

        if ($redirect) {
            // Set flash message
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

        // Delete all non-cascading relations
        if (!is_null($model->non_cascading_relations)) {
            foreach ($model->non_cascading_relations as $relation) {
                $model->$relation()->detach();
            }
        }

        // 	Delete the instance
        $model->delete();

        // Set flash message
        $this->flash('The record has been removed');

        //	Return to the index page
        return $this->redirect("index");
    }

    /**
    * Handle the bulk actions
    * @param  Request $request
    * @return Illuminate\Http\Response
    */
    protected function bulk(Request $request)
    {
        if ($records = $this->model('whereIn', 'id', $request->record)->get()) {
            $count = 0;

            foreach ($records as $record) {
                switch ($request->action) {
                    case 'set':
                        $field = $request->type;
                        $record->$field = ($record->$field == 1) ? 0 : 1;
                        $record->save();
                        break;

                    case 'delete':
                        // Delete all non-cascading relations of record
                        if (!is_null($record->non_cascading_relations)) {
                            foreach ($record->non_cascading_relations as $relation) {
                                $record->$relation()->detach();
                            }
                        }
                        // Delete record
                        $record->delete();
                        break;

                    default:
                        break;
                }
                $count++;
            }

            $this->flash($count . ' records handled');

            return back();
        }
    }
}
