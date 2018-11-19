<?php

/**
 * Field types
 * - layout-boolean
 * - layout-date
 * - layout-component-repeater
 * - layout-media-item (if LaravelAdmin/MediaManager installed)
 * - layout-repeater
 * - layout-select
 * - layout-text
 * - layout-textarea
 * - layout-wysiwyg
 */

return [
    'field' => 'layout',

    'views' => 'layout.',

    'components'    =>    [

        [
            'id'        =>    'basic-text',
            'name'      =>    'Basic text',
            'driver'    =>    null,
            'fields'=>    [
                [
                    'id'=>'title',
                    'name' => 'Title',
                    'type' => 'layout-text'
                ],
                [
                    'id' =>'content',
                    'name' => 'Content',
                    'type' => 'layout-wysiwyg'],
            ]
        ]
    ]

];
