<?php

namespace LaravelAdmin\Crud\Layout\Fields;

use LaravelAdmin\Crud\Layout\Field;

class LayoutComponentRepeater extends Field
{
    public function __construct($content, array $config = [])
    {
        parent::__construct($content, $config);

        $this->getContent();
    }

    public function getContent()
    {
        if (empty($this->content) || !is_array($this->content)) {
            $this->content = [];
        }

        $content = collect($this->content)->each(function ($record, $key) {
            $fields = collect($this->config['children'][array_search($record['settings']['type'], array_column($this->config['children'], 'id'))]['fields'])->mapWithKeys(function ($item) use ($record) {
                if (empty($record['content'][$item['id']])) {
                    $content = null;
                } else {
                    $content = $record['content'][$item['id']];
                }

                return [$item['id'] => $this->getFieldDriver($item, $content)];
            });
            $this->content[$key]['content'] = $fields;
        });
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
}
