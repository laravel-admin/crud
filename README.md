# CRUD models in Laravel

This package is experimental, don't use it for production.

##  Installation

This package is not yet available with packagist, so you have to create a repositories section in you composer.json file.

```
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/laravel-admin/crud"
        }
]
```

Now you can add the package with composer

```
composer require laravel-admin/crud
```

Add the service provider to your app.php config file

```
LaravelAdmin\Crud\CrudServiceProvider::class,
```

## Usage

First create a migration, model and admin controller for your module. Add the admin controller as a resource to your routes, like:

```
Route::resource('blog', 'BlogController');
```

### Resource controller

Setup your basic controller for crud methods as follow:

```
<?php

namespace App\Http\Controllers;

use LaravelAdmin\Crud\Controllers\ResourceController;

use App\Blog;

class BlogController extends ResourceController
{
  protected $model = Blog::class;

  protected $singular_name = "blog";
  protected $plural_name = "blogs";

}
```

The ResourceController includes all the crud methods for a Laravel resource controller, like index, create, store, edit, update and destroy. At this point only the show method will not be used.

The view who will be rendered by index,create and edit are default bootstrap and compatible with the views which will be scaffoled by artisan make:auth.

The only thing you have to do is defining your fields and validation. Herefore you can override a couple of methods of the parent controller in your own controller.

#### Validation on store

```
protected function getValidationRulesOnStore()
{
        return [
                'title' => 'required|string|min:6',
                'body'  => 'required|string',
        ];
 }
 ```

#### Defining your fields for the create form

```
protected function getFieldsForCreate()
{
   return [
     [
       'id' => 'title',
       'label' => 'Title',
       'field' => 'text',
     ],
     [
       'id' => 'body',
       'label' => 'Body',
       'field' => 'textarea',
     ]
  ];
}
```

#### Manipulate your payload for store

This method is optional, by default the payload will be the fields which are defined in the getFieldsForCreate method. If you want to manipulate your data, like a password or date format you can implement this method

```
protected function getPayloadOnStore(array $data)
{
  $payload = $this->getPayloadForStoreDefault($data);

  //    If password is given, lets encrypt it, otherwise remove the password from the payload
  if (!empty($payload['password'])) $payload['password'] = bcrypt($payload['password']);
  else unset($payload['password']);

  return $payload
}
```

#### Edit model

For editing your model, the same methods as store are available:

* getValidationRulesOnUpdate
* getFieldsForEdit
* getPayloadOnUpdate

> Note: If your settings for store and update are the same, you only have to define the methods for the update.

#### View your records

For generating the index view, you can define your fields with the getFieldsForList method.

```
protected function getFieldsForList()
{
   return [
     [
       'id' => 'title',
       'label' => 'Title',
     ],
     [
       'id' => 'created_at',
       'label' => 'Created',
       'formatter' => function($model)
        {
          return $model->created_at->format('Y-m-d');
        }
     ]
  ];
}
```

> Note: Each field has an optional formatter property. By default the property of the model defined in the id is shown. You can assign a accessor in the format field as string, or a callback as shown above.


More to come....


# Layout module

This package includes a layout builder for your page.

## Installation

Add the layout config file to your local config folder.

```
php artisan vendor:publish --provider="LaravelAdmin\Crud\CrudServiceProvider::class"
```

Add the Layout vue component to your view instance

```
Vue.component('layout', require('../../../../vendor/laravel-admin/crud/resources/js/components/layout/Layout.vue'));
```

##	Build your components

Within the config file you can build your own components. Default one simple component is included in the config.

Each component is a combination of fields. The type of fields are:

* Textield (layout-text)
* Textarea (layout-textarea)
* Boolean (layout-boolean)
* Selectbox (layout-select)
* Wysiwyg (layout-wysiwyg) (TinyMCE)
* Media item (layout-media-item) (Only available if LaravelAdmin/MediaManager is available)

> note: The selectbox field can have an options attribute, this can be an array or a callback.

### Component drivers

Each component can have a custom driver when you need some extra logic before rendering it into a view.

Add a full namespaced class into the driver attribute of your component config to enable it.

The best practice is to extends the default component class:

```
\LaravelAdmin\Crud\Layout\Component
```

The following methods can be extended or overwritten:

* getContent
* getView
* isActive
* render

### Field drivers

Also each field can have a custom driver, this can be useful when the content from the admin has to be formatted before sending it to the view.

The best practice is to extends the default field class:

```
\LaravelAdmin\Crud\Layout\Field
```

You can add methods or overwrite the toString method.

### Views

Create your view in the folder which is defined in the layout config (default 'layout'). The name of the template is the same of the id of your component, like 'basic-text.blade.php'.

>Note: If you use your own driver, you van set a custom view within the method 'getView'.

## Create your admin controller

Within your module add a LayoutController file like this:

```
<?php

namespace App\Http\Controllers\Admin\Pages;

use LaravelAdmin\Crud\Controllers\LayoutController as BaseController;

class LayoutController extends BaseController
{
    use Shared;

    protected $model = \App\Models\Page::class;
    protected $instance;

    protected $singular_name = 'page';
    protected $plural_name = 'pages';
}

```

Add the following route into your module routes:

```
Route::resource('pages.layout', 'Admin\Pages\LayoutController');
```

If your layout is in the translatable table, the route will be:

```
/admin/pages/1/layout/en
```

If not, it will be:

```
/admin/pages/1/layout
```

##	Render the layout

Add the following trait to the model which has a layout field:

```
\LaravelAdmin\Crud\Traits\HasLayout
```

There a several ways to render your layout. The most easy solutions is to render it directly from your view file:

```
{!! $model->layout()->render() !!}
```

If you want to use blade includes:

```
@foreach ($model->layout()->components() as $component)
	@include($component->getView(), $component->getContent())
@endforeach
```

If you want to have executed the logic in your controller:

```
//	Controller
public function show($id)
{
	$model = \App\Models\Page::findOrFail($id);
	$layout = $model->layout()->components();

	return view('page', compact('model','layout'));
}

// View
{!! $layout->render() !!}
```
