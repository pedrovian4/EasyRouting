<?php

namespace Petcha\EasyRouting\Facades;
use Illuminate\Support\Facades\File;
use Psy\Readline\Hoa\FileException;

class EasyRouting
{
    /**
     * @throws FileException
     */
    public  static  function easyAPIRequire(): void
    {
        $directoryPath = base_path('routes/easy/api');
        if(!File::isDirectory($directoryPath)){
            throw new FileException('You need generate a easy route first');
        }
        $allFiles = File::allFiles($directoryPath);
        foreach ($allFiles as $file) {
            require_once $file->getPathname();
        }
    }
}
