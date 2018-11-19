<?php

namespace LaravelAdmin\Crud\Layout\Fields;

use LaravelAdmin\Crud\Layout\Field;
use LaravelAdmin\MediaManager\Models\Media;

class LayoutMediaItem extends Field
{
    public $object;

    public function __construct($content, array $config = [])
    {
        parent::__construct($content, $config);

        $this->render();
    }

    protected function render()
    {
        $this->object = Media::find($this->content);
    }
}
