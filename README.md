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


