# EasyRouting Library

EasyRouting is a Laravel package designed to simplify the routing process by automatically generating routes based on controller annotations. This library allows you to define your routes directly above your controller methods, making your routing more intuitive and centralized.

## Features
- **Automatic Route Generation**: Generate routes directly from controller annotations.
- **Flexible Configuration**: Easily configure routes with custom paths, HTTP methods, and middleware.
- **Clean and Intuitive**: Keep your routes clean and understandable by defining them where they matter.


## Configuration
add this code to your `config/app.php`
```php
'providers' => [
    // Other Service Providers
    Petcha\EasyRouting\ServiceProvider::class,
],
```

## Usage

To define routes, use annotations in your controller methods. Here's an example of a controller using EasyRouting notations (methods docs).
```php
<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @API
 * @EasyRouting(prefix:'api/v1/easy', middlewares:[auth, guest, test], name:'easy')
 */
class EasyRoutingController extends Controller
{
    /**
     * @Easy(methods:['GET', 'POST'], name:'index', middleware:[gest])
     */
    public function index()
    {
    }

    /**
     * @Easy(methods:['PUT'], path:'edit', name:'edit')
     */
    public function edit()
    {
    }

    /**
     * @Easy(methods:['PATCH'], path:'store', name:'store')
     */
    public function store()
    {
    }
}
```
## Generating Routes
Once your controllers are annotated, run the provided command to generate the route files:
```bash
php artisan easy:routing
```

## Requiring  your easy routes

You don't need to require every route generated you can just add the followiing code to yout "api.php" or "web.php"

```php
\Petcha\EasyRouting\Managers\RequireManager::requireEasyRoutes();
```
## License
This project is open-sourced software licensed under the MIT license.
