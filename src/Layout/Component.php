<?php

namespace LaravelAdmin\Crud\Layout;

use View;
use Illuminate\Database\Eloquent\Model;

class Component
{
    protected $component;
    protected $model;

    protected $config;

    public function __construct(Model $model, array $component, array $config = null)
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
        return config('layout.views') . $this->component['settings']['type'];
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

        return View::make($this->getView(), $this->getContent()->toArray());
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getContent()
    {
        if (empty($this->component['content'])) {
            $this->component['content'] = [];
        }

        $content = collect($this->config['fields'])->mapWithKeys(function ($item) {
            if (empty($this->component['content'][$item['id']])) {
                $content = null;
            } else {
                $content = $this->component['content'][$item['id']];
            }

            return [$item['id'] => $this->getFieldDriver($item, $content)];
        });

        return $content;
    }

    protected function getFieldDriver(array $field, $content)
    {
        $namespace = '\\LaravelAdmin\\Crud\\Layout\\Fields\\';

        $driverOptions = [];

        if (!empty($field['driver'])) {
            $driverOptions[] = $field['driver'];
        }

        if (!empty($field['type'])) {
            $driverOptions[] = $namespace . studly_case($field['type']);
        }

        $driverOptions[] = Field::class;

        foreach ($driverOptions as $driver) {
            if (class_exists($driver)) {
                return new $driver($content, $field ?: []);
            }
        }

        return null;
    }

    public function __toString()
    {
        return '';
    }
}
