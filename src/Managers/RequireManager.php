<?php

namespace Petcha\EasyRouting\Managers;

use Illuminate\Support\Facades\File;
use Exception;

class RequireManager
{
    /**
     * Require all route files in the 'easy' directory.
     *
     * @throws Exception
     */
    public static function requireEasyRoutes(): void
    {
        $directoryPath = base_path('routes/easy');

        if (!File::isDirectory($directoryPath) || count(File::files($directoryPath)) === 0) {
            throw new Exception("The 'easy' route directory does not exist or is empty.");
        }

        $routeFiles = File::allFiles($directoryPath);
        foreach ($routeFiles as $file) {
            require $file->getPathname();
        }
    }
}
