<?php

namespace LaravelAdmin\Crud\Layout;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\View\View;

class Component
{
    protected $component;
    protected $model;

    protected $config;
    protected $settings;

    public function __construct(string $field, Model $model, array $component, array $config = null)
    {
        $this->field = $field;
        $this->model = $model;
        $this->component = collect($component);
        $this->settings = isset($this->component['model']) ? json_decode($this->component['model']->settings, true) : $this->component['settings'];
        $this->config = $config;
    }

    public function isActive()
    {
        return !empty($this->settings['active']);
    }

    public function getComponentModel()
    {
        return ($this->component['model']) ?: null;
    }

    public function getView()
    {
        return config("{$this->field}.views") . $this->settings['type'];
    }

    public function viewExists()
    {
        return View::exists($this->getView());
    }

    public function getResource()
    {
        return config("{$this->field}.resources") . Str::studly($this->settings['type']);
    }

    /**
     * Render component and return as HTML
     *
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

    public function getSettings()
    {
        return $this->settings;
    }

    public function getContent()
    {
        if (isset($this->component['model'])) {
            $component_content = json_decode($this->component['model']->content, true);
        } else {
            $component_content = isset($this->component['content']) ? $this->component['content'] : null;
        }

        $content = collect($this->config['fields'])->mapWithKeys(function ($item) use ($component_content) {
            if (is_null($component_content) || empty($component_content[$item['id']])) {
                $content = null;
            } else {
                $content = $component_content[$item['id']];
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
            $driverOptions[] = $namespace . Str::studly($field['type']);
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
