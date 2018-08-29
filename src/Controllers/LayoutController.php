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
        //	Get the model instance
        $model = $this->getModelInstance($id);

        if ($request->ajax()) {
            return $model;
        }

        $settings = (new \LaravelAdmin\Crud\Layout\Config())->all();

        $translation = null;
        $foreign_key = null;

        //	Render the view
        return view('crud::templates.layout', $this->parseViewData(compact('model', 'settings', 'translation', 'foreign_key')));
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
        $parent = $this->getModelInstance($id);
        $select_parent_name = (property_exists($this, 'parent_name')) ? $this->parent_name : 'name';
        $parent_name = $parent->$select_parent_name;
        $has_translation = $parent->hasTranslation($translation);
        $model = $parent->translateOrNew($translation);
        $foreign_key = snake_case(class_basename($this->model)) . '_id';

        if ($request->has('copy')) {
            if ($copyfrom = $this->getModelInstance($id)->translateOrNew($request->copy)) {
                $model->fill(['layout' => $copyfrom->layout]);
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
        return view('crud::templates.layout', $this->parseViewData(compact('select_parent_name', 'parent_name', 'model', 'settings', 'has_translation', 'translation', 'foreign_key')));
    }

    /**
     * Store the layout
     * @param  Request $request [description]
     * @param  int  $page_id [description]
     * @return Response
     */
    public function store(Request $request, $id)
    {
        //	Validate the request with the specified validation rules and messages
        $validation = $this->setupValidation(['layout' => 'array']);
        $this->validate($request, $validation->rules, $validation->messages);

        //	Get the model instance
        $model = $this->getModelInstance($id);

        $model->layout = $request->layout;
        // Add user_id to payload
        if (\Schema::hasColumn($this->model()->getTable(), 'updated_by')) {
            $model->updated_by = \Auth::user()->id;
        }
        $model->save();

        $this->flash('The layout is succesfully saved.', 'success');

        return ['status' => 'success'];
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
        $validation = $this->setupValidation(['layout' => 'array']);
        $this->validate($request, $validation['rules'], $validation['messages']);

        //	Get the model instance
        $model = $this->getModelInstance($id);

        $payload = ['layout' => $request->layout];

        // Add user_id to payload
        if (\Schema::hasColumn($this->model()->getTable(), 'updated_by')) {
            $payload['updated_by'] = \Auth::user()->id;
        }
        $model->translateOrNew($translation)->fill($payload);
        $model->save();

        $this->flash('The layout is succesfully saved.', 'success');

        return ['status' => 'success'];
    }

    private function setupValidation(array $default)
    {
        $validation_rules = $default;
        $validation_messages = [];
        $config = config('layout');
        foreach ($config['components'] as $value) {
            foreach ($value['fields'] as $field) {
                if ($field['type'] === 'layout-repeater') {
                    foreach ($field['children'] as $child_field) {
                        if (isset($child_field['validate_rule'])) {
                            $validation_rules[] = [
                                "layout.*.content.{$field['id']}.*.{$child_field['id']}" => $child_field['validate_rule']
                            ];

                            if (isset($child_field['validate_message'])) {
                                foreach ($child_field['validate_message'] as $rule => $message) {
                                    $validation_messages[] = [
                                        "layout.*.content.{$field['id']}.*.{$child_field['id']}.{$rule}" => $message
                                    ];
                                }
                            } else {
                                $name = strtolower($child_field['name']);
                                $validation_messages[] = [
                                    "layout.*.content.{$field['id']}.*.{$child_field['id']}.required" => "The {$name} field is required."
                                ];
                            }
                        }
                    }
                } elseif (isset($field['validate_rule'])) {
                    $validation_rules[] = [
                        "layout.*.content.{$field['id']}" => $field['validate_rule']
                    ];

                    if (isset($field['validate_message'])) {
                        foreach ($field['validate_message'] as $rule => $message) {
                            $validation_messages[] = [
                                "layout.*.content.{$field['id']}.{$rule}" => $message
                            ];
                        }
                    } else {
                        $name = strtolower($field['name']);
                        $validation_messages[] = [
                            "layout.*.content.{$field['id']}.required" => "The {$name} field is required."
                        ];
                    }
                }
            }
        }

        return [
            'rules' => array_collapse($validation_rules),
            'messages' => array_collapse($validation_messages)
        ];
    }
}
