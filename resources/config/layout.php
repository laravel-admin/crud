<?php

/**
 * Field types
 * - layout-text
 * - layout-textarea
 * - layout-boolean
 * - layout-select
 * - layout-wysiwyg
 * - layout-media-item (if LaravelAdmin/MediaManager installed)
 */

return [

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
