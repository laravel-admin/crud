<?php

namespace LaravelAdmin\Crud\Layout;

class Config
{
    protected $data;

    public function __construct(string $config = 'layout', array $data = null)
    {
        $this->data = $data ?: config($config);
    }

    public function all()
    {
        $return = [];

        foreach ($this->data['components'] as $key => $value) {
            foreach ($value['fields'] as $fieldId => $field) {
                if (isset($field['options']) && !is_array($field['options']) && is_callable($field['options'])) {
                    $this->data['components'][$key]['fields'][$fieldId]['options'] = $field['options']();
                } elseif (isset($field['options']) && isset($field['options']['model']) && class_exists($field['options']['model'])) {
                    $this->data['components'][$key]['fields'][$fieldId]['options'] = (new $field['options']['model'])::pluck($field['options']['value'], $field['options']['key'])->all();
                } elseif ($field['type'] === 'layout-repeater') {
                    foreach ($field['children'] as $childId => $field) {
                        if (isset($field['options']) && !is_array($field['options']) && is_callable($field['options'])) {
                            $this->data['components'][$key]['fields'][$fieldId]['children'][$childId]['options'] = $field['options']();
                        } elseif (isset($field['options']) && isset($field['options']['model']) && class_exists($field['options']['model'])) {
                            $this->data['components'][$key]['fields'][$fieldId]['children'][$childId]['options'] = (new $field['options']['model'])::pluck($field['options']['value'], $field['options']['key'])->all();
                        }
                    }
                }
            }
        }

        return $this->data;
    }
}
