<?php

namespace Petcha\EasyRouting\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Petcha\EasyRouting\Analyzers\NotationAnalyzer;
use Petcha\EasyRouting\Contracts\NotationExceptionInterface;
use Petcha\EasyRouting\Facades\NotationFacade;
use Petcha\EasyRouting\Facades\RoutingFacade;
use Symfony\Component\VarDumper\VarDumper;

class EasyRoutingCommand extends Command
{
    protected $signature = 'easy:routing {--controller= : The namespace of the controller to generate routes for}
                                      {--directory= : The subdirectory within Http/Controllers}';
    protected $description = 'Don\'t bother with routes files anymore';

    /**
     * @return void
     */
    public function handle(): void
    {
        $controller = $this->option('controller') ?: $this->getControllerChoice($this->option('directory'));
        $this->info("Analyzing Easy Notation on: <comment>$controller</comment>".PHP_EOL);
        try {
            $analysisResult = NotationFacade::analyze($controller);
            $this->info(VarDumper::dump($analysisResult));
            $this->createRouteFile($controller, $analysisResult);
        } catch (NotationExceptionInterface $exception) {
            $this->error($exception->getVerboseMessage($controller));
            exit;
        }
    }

    /**
     * @param string|null $dir
     * @return string
     */
    private function getControllerChoice(?string $dir): string
    {
        $controllers = $this->getControllers($dir);
        return $this->choice('Choose your controller: ', $controllers);
    }

    /**
     * @param string|null $dir
     * @return array
     */
    private function getControllers(?string $dir): array
    {
        $controllerDir = app_path("Http/Controllers/$dir");
        return array_map(fn($file) => Str::replace("/var/www/html/app/", "", $file), File::allFiles($controllerDir));
    }

    /**
     * @param string $controller
     * @param array $analysisResult
     * @return void
     */
    private function createRouteFile(string $controller, array $analysisResult): void
    {
        $controller = Str::replace(".php", "", $controller);
        $controllerName = class_basename($controller);
        $fileName = strtolower(str_replace('Controller', '', $controllerName)) . '.php';
        $directoryPath = base_path('routes/easy');

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0777, true, true);
        }

        $filePath = $directoryPath . '/' . $fileName;

        $routeContent = "<?php\n\n";
        $routeContent .= "Route::prefix('" . $analysisResult['controller']['path'] . "')\n";
        $routeContent .= "    ->middleware([" . implode(',', array_map(fn($mw) => "'$mw'", $analysisResult['controller']['middleware'])) . "])\n";
        $routeContent .= "    ->controller(App\\" . Str::replace("/", "\\", $controller) . "::class)\n";
        $routeContent .= "    ->group(function () {\n";

        foreach ($analysisResult['route'] as $route) {
            $routeMethods = $route['methods'];
            $routePath = $route['path'];
            $routeName =  $analysisResult['controller']['name'] . '.'. $route['name'];
            $methodName = $route['function'];
            $middlewareArray = $route['middleware'];

            $routeContent .= "        Route::match(['" . implode("', '", $routeMethods) . "'], '$routePath', '$methodName')->name('{$routeName}')";
            if (!empty($middlewareArray)) {
                $routeContent .= "->middleware([" . implode(',', array_map(fn($mw) => "'$mw'", $middlewareArray)) . "])";
            }
            $routeContent .= ";\n";
        }
        $routeContent .= "    });\n";

        File::put($filePath, $routeContent);
        $this->info("Route file created at: $filePath");
    }

}
