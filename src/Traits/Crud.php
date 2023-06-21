<?php

namespace LaravelAdmin\Crud\Traits;

use Carbon\Carbon;

/**
 * Use this trait in a admin controller
 */
trait Crud
{
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
        $args = func_get_args();

        //	The first argument is the method name, who will be called statically
        $method = array_shift($args);

        if (!$method) {
            return new $this->model;
        }

        //	Execute the class method with the given arguments
        return call_user_func_array($this->model . '::' . $method, $args);
    }

    /**
     * Validate the model name which is specified in the controller
     * @return void
     */
    protected function validateModel()
    {
        //	Check if the modelname is defined
        //	If not, throw an exception
        if (empty($this->model)) {
            throw new \RuntimeException('No model defined in CRUD controller');
        }
        //	Check if the class exists
        //	If not, throw an exception
        if (!class_exists($this->model)) {
            throw new \RuntimeException('Model with name ' . $this->model . ' not found');
        }
        //	TODO: Check if the class is a valid Eloquent Model
    }

    /**
     * Get an instance of a model by the given id
     * @param  int $id
     * @return Collection
     */
    protected function getModelInstance($id)
    {
        return $this->instance = $this->model('findOrFail', $id);
    }

    /**
     * Get the default validation rules when storing a new model
     * This method can be overridden by the controller
     * @return array
     */
    protected function getValidationRulesOnStore()
    {
        return [
            'name' => 'required|string',
        ];
    }

    /**
     * Get the default validation rules when storing a new model
     * This method can be overridden by the controller
     * @return array
     */
    protected function getValidationMessagesOnStore()
    {
        return $this->getValidationRulesOnStore();
    }

    /**
     * Get all valid keys of post data what will be saved in the database
     * @return array
     */
    protected function getPayloadOnStore(array $payload)
    {
        return $this->getPayloadOnStoreDefaults($payload);
    }

    protected function getPayloadOnStoreDefaults(array $payload)
    {
        return $this->formatPayload($this->getFieldsForCreate(), $payload);
    }

    /**
     * Get all valid keys of post data what will be updated in the database
     * @return array
     */
    protected function getPayloadOnUpdate(array $payload)
    {
        return $this->getPayloadOnUpdateDefaults($payload);
    }

    protected function getPayloadOnUpdateDefaults(array $payload)
    {
        return $this->formatPayload($this->getFieldsForEdit(), $payload);
    }

    protected function getRelationsOnUpdate(array $payload)
    {
        $return = [];

        foreach ($this->getFieldsForEdit() as $item) {
            if (strpos($item['id'], '[]') > 0) {
                $relation = str_replace('[]', '', $item['id']);
                $return[$relation] = array_key_exists($relation, $payload) ? $payload[$relation] : null;
            }
        }

        return $return;
    }

    /**
     * Format the valid keys of post data
     * @return array
     */
    private function formatPayload($fields, $payload)
    {
        $return = [];

        foreach ($fields as $item) {
            if (array_key_exists($item['id'], $payload)) {
                switch ($item['field']) {
                    case 'date':
                        $return[$item['id']] = (!is_null($payload[$item['id']])) ? Carbon::createFromFormat('d-m-Y', $payload[$item['id']])->format('Y-m-d') : null;
                        break;

                    default:
                        $return[$item['id']] = $payload[$item['id']];
                        break;
                }
            }
        }

        return $return;
    }

    /**
     * Get the default validation rules when storing a new model
     * @return array
     */
    protected function getValidationRulesOnUpdate()
    {
        return $this->getValidationRulesOnStore();
    }

    /**
     * Get the default validation rules when storing a new model
     * @return array
     */
    protected function getValidationMessagesOnUpdate()
    {
        return $this->getValidationRulesOnUpdate();
    }

    /**
     * Get all fields for the create form
     * @return array
     */
    protected function getFieldsForCreate()
    {
        return [
            [
                'id' => 'name',
                'label' => 'Name',
                'field' => 'text',
            ],
        ];
    }

    /**
     * Get all fields for the edit form
     * @return array
     */
    public function getFieldsForEdit()
    {
        return $this->getFieldsForCreate();
    }

    /**
     * Get all fields the list table
     * @return array
     */
    public function getFieldsForList()
    {
        return [
            ['id' => 'name', 'label' => 'Name'],
            ['id' => 'created_at', 'label' => 'Created', 'formatter' => function ($model) {
                return $model->created_at->format('Y-m-d');
            }],
        ];
    }

    /**
     * The data to transfer default to all views
     * @param  $data
     * @return array
     */
    protected function parseViewData(array $data = [])
    {
        $data['handle_bulk'] = (property_exists($this, 'handle_bulk')) ? $this->handle_bulk : false;
        if ($data['handle_bulk']) {
            $data['submenu_bulk'] = $this->getSubmenuBulk();
        }
        $data['list_order_by'] = (property_exists($this, 'list_order_by')) ? $this->list_order_by : null;
        $data['list_search_on'] = (property_exists($this, 'list_search_on')) ? $this->list_search_on : null;

        $data['singular_name'] = $this->singular_name;
        $data['plural_name'] = (property_exists($this, 'plural_name')) ? $this->plural_name : null;
        $data['route'] = $this->getRouteName();
        $data['parent_route'] = $this->getRouteName(2);
        $data['layout'] = $this->layout();
        $data['submenu'] = $this->getSubmenu();
        $data['additional_submenu'] = $this->getAdditionalSubmenu();

        $data['allow_search'] = (property_exists($this, 'allow_search')) ? $this->allow_search : true;
        $data['allow_create'] = (property_exists($this, 'allow_create')) ? $this->allow_create : true;
        $data['allow_delete'] = (property_exists($this, 'allow_delete')) ? $this->allow_delete : true;
        $data['allow_edit'] = (property_exists($this, 'allow_edit')) ? $this->allow_edit : true;

        //	TODO: What else

        return $data;
    }

    /**
     * Get submenu
     *
     * @return
     */
    protected function getSubmenu()
    {
        // Check if we have different submenu
        if (property_exists($this, 'submenu')) {
            $action = $this->submenu;
        } else {
            $current_route = \Route::currentRouteName();

            //	Make an array based on the dots in the route
            $route_parts = explode('.', $current_route);

            //	Remove the last item of the array (the action)
            $action = array_pop($route_parts);
        }

        if ($action == 'index' && method_exists($this, 'getSubmenuForList')) {
            return $this->getSubmenuForList();
        }
        if ($action == 'create' && method_exists($this, 'getSubmenuForCreate')) {
            return $this->getSubmenuForCreate();
        }
        if (($action == 'edit' || $action == 'show') && method_exists($this, 'getSubmenuForEdit')) {
            return $this->getSubmenuForEdit();
        }

        return [];
    }

    /**
     * Get additional submenu
     *
     * @return
     */
    protected function getAdditionalSubmenu()
    {
        // Check if we have different submenu
        if (property_exists($this, 'additional_submenu')) {
            $action = $this->additional_submenu;
        } else {
            $current_route = \Route::currentRouteName();

            //	Make an array based on the dots in the route
            $route_parts = explode('.', $current_route);

            //	Remove the last item of the array (the action)
            $action = array_pop($route_parts);
        }

        if ($action == 'index' && method_exists($this, 'getAdditionalSubmenuForList')) {
            return $this->getAdditionalSubmenuForList();
        }
        if ($action == 'create' && method_exists($this, 'getAdditionalSubmenuForCreate')) {
            return $this->getAdditionalSubmenuForCreate();
        }
        if (($action == 'edit' || $action == 'show') && method_exists($this, 'getAdditionalSubmenuForEdit')) {
            return $this->getAdditionalSubmenuForEdit();
        }

        return [];
    }

    /**
     * Get submenu for bulk actions
     *
     * @return
     */
    protected function getSubmenuBulk()
    {
        if (method_exists($this, 'getFieldsForBulk')) {
            return $this->getFieldsForBulk();
        }
    }

    protected function getRouteName($pop = 1)
    {
        //	If custom route defined in controller, use this
        if (property_exists($this, 'route') && !empty($this->route)) {
            return trim($this->route, '.') . '.';
        }

        //	Get the current route
        $current_route = \Route::currentRouteName();

        //	Make an array based on the dots in the route
        return collect(explode('.', $current_route))
                ->reverse()
                ->slice($pop)
                ->reverse()
                ->implode('.') . '.';
    }

    /**
     * A helper for calling a route only based on the crud action
     * @param  string $action
     * @param  $params
     * @return Route
     */
    protected function route($action, $params = null)
    {
        return $params ? route($this->getRouteName() . $action, $params) : route($this->getRouteName() . $action);
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

    /**
     * Determine the master layout file
     * @return string
     */
    protected function layout()
    {
        //	If custom layout defined in controller, use this
        if (property_exists($this, 'layout') && !empty($this->layout)) {
            return $this->layout;
        }

        if (class_exists('\LaravelAdmin\Base\BaseServiceProvider')) {
            return 'admin::master';
        }

        return 'layouts.admin';
    }

    /**
     * Send flash message if installed
     * @return [type] [description]
     */
    protected function flash($message, $type = 'success')
    {
        if (!function_exists('flash')) {
            return;
        }

        flash($message, $type);
    }
}
