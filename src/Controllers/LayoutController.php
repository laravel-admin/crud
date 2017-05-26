<?php

namespace LaravelAdmin\Crud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelAdmin\Crud\Traits\Crud;

class LayoutController extends Controller
{
    use Crud;

    /**
     * Show the layout
     * @param  int $page_id
     * @return Response;
     */
    public function index(Request $request, $id)
    {
        return $this->show($request, $id, app()->getLocale());
    }

    /**
     * Use the create method to return the available components
     * @return Response
     */
    public function create()
    {
        return (new \LaravelAdmin\Crud\Layout\Config())->all();
    }

    /**
     * Show the layout
     * @param  int $page_id
     * @return Response;
     */
    public function show(Request $request, $id, $translation)
    {
        //	Get the model instance
        $model = $this->getModelInstance($id)->translateOrNew($translation);
        $foreign_key = snake_case(class_basename($this->model))."_id";

        if ($request->has('copy')) {
            if ($copyfrom = $this->getModelInstance($id)->translateOrNew($request->copy)) {
                $model->fill(['layout'=>$copyfrom->layout]);
                $model->save();

                $this->flash('The layout is succesfully copied', 'success');

                return back();
            }
        }

        $model->$foreign_key = $id;

        if ($request->ajax()) {
            return $model;
        }

        $settings = (new \LaravelAdmin\Crud\Layout\Config())->all();

        //	Render the view
        return view('crud::templates.layout', $this->parseViewData(compact('model', 'settings', 'translation', 'foreign_key')));
    }

    /**
     * Store the layout
     * @param  Request $request [description]
     * @param  int  $page_id [description]
     * @return Response
     */
     public function update(Request $request, $id, $translation)
     {
         //	Validate the request with the specified validation rules and messages
         $this->validate($request, ['layout'=>'array']);

         //	Get the model instance
         $model = $this->getModelInstance($id);

         $model->translateOrNew($translation)->fill(['layout'=>$request->layout]);
         $model->save();

         $this->flash('The layout is succesfully saved', 'success');

         return ['status'=>'success'];
     }
}
