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

First create migratio, model and admin controller for your module. Add the admin controller as a resource to your routes, like:

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




