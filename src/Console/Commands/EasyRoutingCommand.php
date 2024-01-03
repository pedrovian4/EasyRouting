<?php

namespace Petcha\EasyRouting\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EasyRoutingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easy:routing {--controller= : The namespace of the controller to generate routes for}
                                      {--directory= : The subdirectory within Http/Controllers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Don\'t bother with routes files anymore';
    protected string $easyRoutingAscii = "
    /**
        *     ____  ____  ____  ____  _________  ____  ____  ____  ____  ____  ____  ____
        *    ||E ||||A ||||S ||||Y ||||       ||||R ||||O ||||U ||||T ||||I ||||N ||||G ||
        *    ||__||||__||||__||||__||||_______||||__||||__||||__||||__||||__||||__||||__||
        *    |/__\\||/__\\||/__\\||/__\\||/_______\\||/__\\||/__\\||/__\\||/__\\||/__\\||/__\\||/__\\|
        *
        *    Don\'t bother with routes files anymore
    **/";


    /**
     * @throws ReflectionException
     */
    public function handle(): void
    {

        $specificController = $this->option('controller');
        $directory = $this->option('directory')?:null;
        if (empty($specificController)) {
          try {
              $specificController = $this->choice(
                  'Choose a controller to generate routes: ',
                  $this->getControllers($directory));
          }catch ( FileException $exception){
              $this->error($exception->getMessage());
              die();
          }
        }


        $this->processController($specificController);

        $this->info('Routing Generation starting ...');



    }

    protected function getControllers(?string $dir): array
    {
        $controllers = [];
        $controllersPath = "Http/Controllers/$dir";
        $controllerDirectory = app_path($controllersPath);
        $files = File::allFiles($controllerDirectory);

        if(count($files)<2){
            throw new FileException('You can user --directory argument for directories that contains only one file, use --controller instead passing it full namespace');
        }

        foreach ($files as $file) {
            $filename = $file->getRelativePathname();

            if (!str_ends_with($filename, '.php')) {
                continue;
            }

            $class = $this->convertPathToNamespace($file);

            if (class_exists($class)){
                $controllers[] = $class;
            }
        }

        return $controllers;
    }

    protected function convertPathToNamespace($file): string
    {
        $relativePath = $file->getRelativePathname();
        $className = str_replace('.php', '', $relativePath);
        $namespace = str_replace('/', '\\', $className);
        return "App\\Http\\Controllers\\" . $namespace;
    }

    /**
     * @throws ReflectionException
     */
    protected function processController($controllerClass): void
    {
        $reflector = new ReflectionClass($controllerClass);
        $classDocComment = $reflector->getDocComment();
        if (str_contains($classDocComment, '@API')) {
            $this->info("Processing API controller: $controllerClass");
            $this->processControllerAPI($controllerClass);
        }


        foreach ($reflector->getMethods() as $method) {
            $methodDocComment = $method->getDocComment();
            if (str_contains($methodDocComment, '@Easy')) {
                $this->info("Found @EasyRouting in {$controllerClass}@{$method->name}");
            }
        }

    }

    protected  function  createEasyDirIfNotExists(bool $api = true): string
    {

        $baseDir = 'routes/easy/';
        File::isDirectory($baseDir) or  File::makeDirectory($baseDir);

        $routesDir = $baseDir . ($api? 'api': 'default');
        File::isDirectory($routesDir) or  File::makeDirectory($routesDir);

        return $routesDir;
    }

    protected  function  createControllerRouteFileIfNotExists(string $controllerNamespace, bool $api = true): string
    {
        $directory = $this->createEasyDirIfNotExists($api);
        $controllerName = class_basename($controllerNamespace);
        $controllerName = Str::before($controllerName, 'Controller');
        $easyRouteControllerFile = Str::lower(Str::before($controllerName, 'Controller'));
        $fileName = "$directory/$easyRouteControllerFile.php";
        if(File::exists($fileName)){
            File::delete($fileName);
        }

        $content = "<?php\n$this->easyRoutingAscii ";
        File::put($fileName, $content);

        return  $fileName;
    }

    /**
     * @throws ReflectionException
     */
    protected  function  processControllerAPI(string $controllerClass): array
    {
        $filePath  = $this->createControllerRouteFileIfNotExists($controllerClass, true);
        $controllerRoutes = $this->createControllerRoutes($controllerClass);
        $fileContent  = $this->creatingGroupingRoutes($controllerClass,$controllerRoutes);
        File::append($fileContent,$filePath);
        return [];
    }

    /**
     * @throws ReflectionException
     */
    protected function creatingGroupingRoutes(string $fullClassNamespace,string $controllerRoutes): string
    {
        $reflector = new ReflectionClass($fullClassNamespace);
        $classDocComment = $reflector->getDocComment();
        $prefix = '';
        $middlewares = [];
        if ($classDocComment !== false) {
            preg_match('/@EasyRouting\((.*)\)/', $classDocComment, $matches);
            if (!empty($matches) && isset($matches[1])) {
                $params = $this->parseAnnotationParams($matches[1]);

                $prefix = $params['prefix'] ?? '';
                $middlewares = $params['middlewares'] ?? [];
            }
        }

        $routeContent = "\nRoute::controller($fullClassNamespace::class)";

        if (!empty($prefix)) {
            $routeContent .= "->prefix('$prefix')";
        }

        if (!empty($middlewares)) {
            if (is_array($middlewares)) {
                $middlewareList = implode("', '", $middlewares);
                $routeContent .= "->middleware(['$middlewareList'])";
            } else {
                $this->error("Middlewares devem ser um array.");
                die();
            }
        }


        $routeContent .= "->group(function () {\n";
        $routeContent .= $controllerRoutes;
        $routeContent .= "});\n";

        return $routeContent;
    }
    protected function parseAnnotationParams(string $annotationContent): array
    {
        $params = [];
        $parts = explode(',', str_replace(' ', '', $annotationContent));

        foreach ($parts as $part) {
            list($key, $value) = explode(':', $part, 2);

            if ($key === 'middlewares') {
                $middlewares = trim($value, "[]' ");
                $params[$key] = explode("','", $middlewares);
            } else {
                $params[$key] = trim($value, "' ");
            }
        }

        return $params;
    }


    /**
     * @throws ReflectionException
     */
    protected function createControllerRoutes(string $controllerClass): string
    {
        $routeContent = "";
        $reflector = new ReflectionClass($controllerClass);
        $methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $docComment = $method->getDocComment();
            if (str_contains($docComment, '@Easy')) {
                $path = $this->parsePathFromDocComment($docComment);
                foreach ($methods as $httpMethod) {
                    $httpMethod = strtolower($httpMethod) === '*' ? 'any' : strtolower($httpMethod);
                    $routePath = $path ?: Str::snake($method->name);
                    $routeContent .= "    Route::{$httpMethod}('{$routePath}', '{$method->name}');\n";
                }
            }
        }

        return $routeContent;
    }

    protected function parseMethodsFromDocComment(string $docComment): array
    {
        preg_match('/@Easy\(methods:\[([^\]]+)\]/i', $docComment, $matches);
        return isset($matches[1]) ? explode(',', str_replace(["'", " "], "", $matches[1])) : [];
    }

    protected function parsePathFromDocComment(string $docComment): ?string
    {
        preg_match('/@Easy\(.*path:\'([^\']+)\'/i', $docComment, $matches);
        return $matches[1] ?? null;
    }


}
