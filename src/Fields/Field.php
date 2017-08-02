<?php

namespace LaravelAdmin\Crud\Fields;

use Illuminate\Support\Facades\View;

/**
 * Class with all shared methods for the fields
 */

//	TODO: Create a contract for fields, for making other people easy to create their own field types

abstract class Field
{
    protected $config;

    /**
     * Dave the config as an object property
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Validate and return the path of the view for the current field
     * @return string
     */
    public function view()
    {
        //	Convention of the view is that it wil be stored in the fields folder
        //	of the CRUD views. The name of the view has to be the snake cased
        //	version of the class name
        $view_path = (property_exists($this, 'view_path') ? $this->view_path : "crud::fields." . str_replace('_', '-', snake_case(class_basename(get_class($this)))));

        //	Does the view exists
        if (!View::exists($view_path)) {
            return null;
        }

        return $view_path;
    }

    /**
     * Get attributes from the config
     * @param  string $method
     * @param  array $args
     * @return all
     */
    public function __call($method, $args)
    {
        if (isset($this->config[$method])) {
            return $this->config[$method];
        }

        return null;
    }

    /**
     * Get the value of the field in the current model instance
     * @param  Model $model
     * @return string
     */
    public function value($model)
    {
        $prop = $this->id();
        return $model->$prop;
    }

    /**
     * Get the value what will be shown in the form
     * @param  Model $model
     * @return string
     */
    public function format($model)
    {
        //	By default the attributed of the id of the field will be returned
        if (!isset($this->config['formatter'])) {
            $prop = $this->id();
            return $model->$prop;
        }

        //	If a formatter is defined, the attribute or accesor in the model will be returned
        if (is_string($this->config['formatter'])) {
            $prop =  $this->config['formatter'];
            return $model->$prop;
        }

        //	The formatter is a closure, so execute this callback!
        if (is_callable($this->config['formatter'])) {
            return $this->config['formatter']($model);
        }
    }
}
