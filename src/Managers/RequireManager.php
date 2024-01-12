<?php

namespace Petcha\EasyRouting\Managers;

use Illuminate\Support\Facades\File;
use Exception;

class RequireManager
{
    /**
     * Require all route files in the 'easy' directory.
     *
     * @param string $type The type of routes to include ('web' or 'api').
     * @throws Exception
     */
    public static function requireEasyRoutes(string $type): void
    {
        $directoryPath = base_path('routes/easy');
        if (!File::isDirectory($directoryPath) || count(File::files($directoryPath)) === 0) {
            throw new Exception("The 'easy' route directory does not exist or is empty.");
        }
        $routeFiles = File::allFiles($directoryPath);
        foreach ($routeFiles as $file) {
            $filePath = $file->getPathname();
            if (str_contains($filePath, $type)) {
                require $filePath;
            }
        }
    }
}
