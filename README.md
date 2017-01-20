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
Route::resource('blog', 'YourController');
```

Go to your controller and append the CrudController trait:

```
<?php

namespace App\Http\Controllers;

use LaravelAdmin\Crud\CrudController;

class YourController extends Controller
{
  use CrudController;
}
```

The trait includes all the crud methods for a Laravel resource controller, like index, create, store, edit, update and destroy. At this point only the show method will not be used.

The view who will be rendered by index,create and edit are default bootstrap and compatible with the views which will be scaffoled by artisan make:auth.

The only thing you have to do is defining your fields and validation. Herefore you can override a couple of methods of the trait

* getValidationRulesOnStore
* getValidationRulesOnUpdate

* getFieldsForCreate
* getFieldsForEdit
* getFieldsForList

More to come....


