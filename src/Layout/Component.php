<?php

namespace LaravelAdmin\Crud\Layout;

use View;
use Illuminate\Database\Eloquent\Model;

class Component
{
    protected $component;
    protected $model;

    protected $config;

    public function __construct(Model $model, array $component, array $config=null)
    {
        $this->model = $model;
        $this->component = collect($component);
        $this->config = $config;
    }

    public function isActive()
    {
        return !empty($this->component['settings']['active']);
    }

    public function getView()
    {
        return config('layout.views').$this->component['settings']['type'];
    }

    public function viewExists()
    {
        return View::exists($this->getView());
    }

    /**
     * Render component and return as HTML
     * @return string
     */
    public function render()
    {
        if (!$this->isActive()) {
            return null;
        }
        
        if (!$this->viewExists()) {
            return null;
        }

        return View::make($this->getView(), $this->getContent());
    }

    public function getContent()
    {
        if (empty($this->component['content'])) {
            $this->component['content'] = [];
        }

        $content = collect($this->component['content'])->map(function ($item, $key) {
            if ($driver = $this->getFieldDriver($key, $item)) {
                return $driver;
            }

            return $item;
        });

        return $content;
    }

    protected function getFieldDriver($field, $content)
    {
        $namespace = "\\LaravelAdmin\\Crud\\Layout\\Fields\\";
        $config = collect($this->config['fields'])->where('id', $field)->first();

        $driverOptions = [];

        if (!empty($config['driver'])) {
            $driverOptions[] = $config['driver'];
        }

        if (!empty($config['type'])) {
            $driverOptions[] = $namespace.studly_case($config['type']);
        }

        $driverOptions[] = Field::class;

        foreach ($driverOptions as $driver) {
            if (class_exists($driver)) {
                return new $driver($content, $config);
            }
        }

        return null;
    }

    public function __toString()
    {
        return "";
    }
}
