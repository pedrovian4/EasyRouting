# EasyRouting Library (In development)

EasyRouting is a Laravel package designed to simplify the routing process by automatically generating routes based on controller annotations. This library allows you to define your routes directly above your controller methods, making your routing more intuitive and centralized.

## Features

- **Automatic Route Generation**: Generate routes directly from controller annotations.
- **Flexible Configuration**: Easily configure routes with custom paths, HTTP methods, and middleware.
- **Clean and Intuitive**: Keep your routes clean and understandable by defining them where they matter.



## Usage


### Annotate Your Controller

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @API
 * @EasyRouting(prefix:'api/v1/easy', middlewares:['auth'])
 **/
class MyController extends Controller
{
    /**
     * @Easy(methods:['GET', 'POST'])
     **/
    public function index()
    {
    }

    /**
     * @Easy(methods:['*'], path:'edit')
     **/
    public function edit()
    {
    }

    /**
     * @Easy(methods:['PATCH'], path:'store')
     **/
    public function store()
    {
    }
}
```

### Run the Command

After annotating your controllers, use the following Artisan command to generate the routes:


```bash
php artisan easy:routing

# For a specific controller
php artisan easy:routing --controller=App\\Http\\Controllers\\MyController

# For controllers in a specific directory
php artisan easy:routing --directory=subdirectory_name

```


### Contributing

Contributions are welcome! If you would like to help improve, fix, or discuss the EasyRouting library, please feel free to open an issue or submit a pull request on the project's GitHub repository.
