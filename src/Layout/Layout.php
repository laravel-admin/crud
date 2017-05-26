<?php

/*
* Example data object
*/

// $data = [
// 	[
// 		'settings' => [
// 			'name' => 'Name of component 1',
// 			'active' => true,
// 		],
// 		'content' => [
// 			'title' => 'The title of the component',
// 			'content' => 'The content of the component',
// 		]
// 	],
// 	[
// 		'settings' => [
// 			'name' => 'Name of component 2',
// 			'active' => true,
// 		],
// 		'content' => [
// 			'title' => 'The title of the component',
// 			'content' => 'The content of the component',
// 		]
// 	]
// ];


/*
* Example config
*/

// return [
//
//     'views' => 'layout.',
//
//     'components'    =>    [
//
//         [
//             'id'        =>    'basic-text',
//             'name'      =>    'Basic text with title',
//             'driver'    =>     LaravelAdmin\Crud\Layout\Drivers\BasicText::class,
//             'fields'=>    [
//                 [
//                 		'id'=>'title',
//                 		'name' => 'Title',
//                 		'type' => 'layout-text'
//                 ],
//                 [
//                 		'id' =>'content',
//                 		'name' => 'Content',
//                 		'type' => 'layout-wysiwyg',
//                 		'driver' => LaravelAdmin\Crud\Layout\MyCustomFieldClass::class,
//                 ]
//             ]
//         ]
// 	]
// ];

namespace LaravelAdmin\Crud\Layout;

use Illuminate\Database\Eloquent\Model;

class Layout
{
    /**
     * The current post
     * @var Post
     */
    protected $model;

    /**
     * The ACF Field
     * @var Collection
     */
    protected $data;

    /**
     * The components will be saved
     * @var Collection
     */
    protected $components;

    /**
     * Initializing the Flex library
     * @param Model   $post
     * @param string $field
     */
    public function __construct(Model $model, $field="layout")
    {
        //	Save the post as an object variable
        $this->model = $model;

        //	Get the Flexible Content collection from the post
        $this->data = $this->getFieldData($field);
    }

    protected function getFieldData($field)
    {
        //  Todo: Cache it
        return collect($this->model->$field);
    }

    /**
     * Check all components
     * @return Collection
     */
    public function components()
    {
        //	Create per component a path to the view
        return $this->components = $this->data->map(function ($item) {
            if (!empty($item['settings']['type']) && $config = $this->getConfigForComponent($item['settings']['type'])) {
                return $this->getDriverForComponent($item, $config);
            }
            return null;
        //	Filter out items, which have not a matching template partial
        })->filter(function ($item) {
            return !is_null($item);
        });
    }

    /**
     * Render all components
     * @return string
     */
    public function render()
    {
        return $this->components()->reduce(function ($carry, $item) {
            return $carry.$item->render();
        });
    }

    /**
     * Get the config from layout.components for the specific component
     * @param  string $id
     * @return Collection
     */
    public function getConfigForComponent($id)
    {
        return collect(config('layout.components'))->filter(function ($item) use ($id) {
            return !empty($item['id']) && $id == $item['id'];
        })->first();
    }

    /**
     * Get the driver class for the specific component
     * @param  array $field
     * @param  array $config
     * @return Basic
     */
    public function getDriverForComponent($component, $config)
    {
        if (empty($config['driver']) || !class_exists($config['driver'])) {
            $config['driver'] = \LaravelAdmin\Crud\Layout\Component::class;
        }

        if (!class_exists($config['driver'])) {
            return null;
        }

        return new $config['driver']($this->model, $component, $config);
    }
}
