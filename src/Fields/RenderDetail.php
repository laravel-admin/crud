<?php

namespace LaravelAdmin\Crud\Fields;

use Illuminate\Support\Str;

/**
 * Render the fields of a CRUD model for the create and edit form
 */
class RenderDetail
{
    protected $fields;
    protected $namespace = '\\LaravelAdmin\\Crud\\Fields\\';

    /**
     * Send an array with the config of all fields to show
     */
    public function __construct(array $fields)
    {
        $this->fields = collect($fields);
    }

    /**
     * Return all values of a model instance
     *
     * @return \Illuminate\Support\Collection
     */
    public function values()
    {
        //	Map over every field and return an instance of the field driver for each item
        return $this->fields->map(function ($item) {
            //	TODO: Does the driver attribute exists?
            $class = $item['field'];

            // Check if we got a string or class
            if (!class_exists($class)) {
                $class = $this->namespace . Str::studly($class);
            }

            // Check if class exists
            if (class_exists($class)) {
                return new $class($item);
            }

            return null;
        });
    }
}
