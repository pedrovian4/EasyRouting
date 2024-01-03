# Notations

_Notations_ are a pivotal feature of the EasyRouting library, allowing developers to elegantly define routing behaviors directly through intuitive code annotations. These notations serve as directives to the EasyRouting engine, instructing it on how to treat different controllers and methods in terms of routing.

## JsonResponse Controllers

For controllers that exclusively return `JsonResponse::class`, you must annotate the controller class with `@API`. This special notation signals that the controller's responses are strictly JSON, facilitating appropriate routing and response handling.

```php 
/**
* @API
**/
class MyController extends Controller
{
    // Controller methods here
}
```

## Group Controller

The `@Easy` and `@Routing` notations provide a robust system for defining route groups and individual route behaviors. These annotations reduce the need for manually editing route files, streamlining the development process.

### @Easy

The `@Easy` notation is used to define a route group with common attributes. It allows you to specify a path prefix, a name prefix, and middleware that apply to all routes within the controller.

- **path:** The base URI path for all routes within the controller.
- **name:** A prefix for all route names within the controller, aiding in route identification.
- **middleware:** An array of middleware that should apply to all routes within the controller.

```php 
/**
* @Easy(path:'api/v1', name:'mycontroller', middleware:['auth'])
**/
class MyController extends Controller
{
    // Controller methods here
}
```

### @Routing

The `@Routing` notation fine-tunes individual routes within a controller, allowing for explicit path, method, name, and middleware definitions.

- **path:** Specifies the URI path for the route. If omitted, it defaults to the method name.
- **methods:** Defines the HTTP methods the route responds to (e.g., `['GET', 'POST']`).
- **name:** Sets a specific name for the route, overriding any prefixes set by `@Easy`.
- **middleware:** Allows for route-specific middleware. Using `[...]` includes all middleware defined in `@Easy`, plus any additional specified here.

```php 
class MyController extends Controller
{
    /**
    * @Routing
    **/
    public function index()
    {
        // Route for GET /api/v1
    }
    
    /**
    * @Routing(path:'update',methods:['PATCH'] ,name:'update_controller')
    **/
    public function update()
    {
        // Route for PATCH /api/v1/update named 'update_controller'
    }
    
    /**
    * @Routing(path:'no', methods:['*'],name:'no_controller', middleware:[])
    **/
    public function noMiddleware()
    {
        // Route for ANY /api/v1/no with no middleware
    }
    
    /**
    * @Routing(path:'custom', methods:['GET'],name:'custom_controller', middleware:['guest'])
    **/
    public function customMiddleware()
    {
        // GET /api/v1/custom with 'guest' middleware
    }
    
    /**
    * @Routing(path:'custom-plus-base', methods:['GET'],name:'custom_plus_base_controller', middleware:[...,'guest'])
    **/
    public function customMiddlewarePlusBaseMiddleware()
    {
        // GET /api/v1/custom-plus-base with base and 'guest' middleware
    }
}
```
###  @Routing Defaults
- **path**: default in @Easy
- **methods**: default is GET
- **name**: default in @Easy plus method name, example: 'easy.index'
- **middleware**: default in @Easy

These notations provide a declarative, clear, and concise way to define routing in your Laravel application, minimizing boilerplate and improving readability and maintainability of your code.
