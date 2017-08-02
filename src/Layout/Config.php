<?php

namespace LaravelAdmin\Crud\Layout;

class Config
{
    protected $data;

    public function __construct(array $data=null)
    {
        $this->data = $data ?: config('layout');
    }


    public function all()
    {
        $return = [];

        foreach ($this->data['components'] as $key=>$value) {
            foreach ($value['fields'] as $fieldId=>$field) {
                if (isset($field['options']) && is_callable($field['options'])) {
                    $this->data['components'][$key]['fields'][$fieldId]['options'] = $field['options']();
                }
            }
        }

        return $this->data;
    }
}
