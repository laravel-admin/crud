<?php

namespace LaravelAdmin\Crud\Fields;

/**
 * Driver for a selectbox field
 */
class SelectParentChild extends Field
{

    /**
     * Get the available options for the selectbox
     * @param  Model $model
     * @return array
     */
    public function options($model)
    {
        //	If the options attribute is a callback
        if (!isset($this->config['options'])) {
            return [];
        }

        if (is_array($this->config['options'])) {
            return $this->config['options'];
        }

        if (is_callable($this->config['options'])) {
            return $this->config['options']($model);
        }

        //	TODO: Create more opportunities then only a callback

        return [];
    }

    /**
     * Get the available options for the selectbox
     * @param  Model $model
     * @return array
     */
    public function current_id($model)
    {
        //	If the options attribute is a callback
        if (is_callable($this->config['current_id'])) {
            return $this->config['current_id']($model);
        }

        return 0;
    }
}
