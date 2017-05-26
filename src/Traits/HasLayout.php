<?php

namespace LaravelAdmin\Crud\Traits;

use LaravelAdmin\Crud\Layout\Layout;

trait HasLayout
{
    public function layout($field="layout")
    {
        return new Layout($this, $field);
    }
}
