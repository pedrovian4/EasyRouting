<?php

namespace Petcha\EasyRouting\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Petcha\EasyRouting\Facades\EasyRoutingGenerator;

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



    public function handle(): void
    {
        $controller = $this->option('controller');
        $dir = $this->option('directory');

        if(!$controller){
            $controllers = $this->getControllers($dir);
            $controller = $this->choice('Choose your controller: ',$controllers);
        }
        $this->info("Analyzing Easy Notation on: <comment>$controller</comment>");


    }


    private function getControllers(?string $dir):array
    {
        $baseControllerPath = "Http/Controllers/$dir";
        $controllerDir = app_path($baseControllerPath);
        return Str::replace("/var/www/html/app/", "",File::allFiles($controllerDir));
    }


}
