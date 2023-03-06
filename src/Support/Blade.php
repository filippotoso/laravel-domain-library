<?php

namespace FilippoToso\Domain\Support;

use DirectoryIterator;
use Illuminate\Support\Facades\Blade as BladeFacade;
use Illuminate\View\Component;

class Blade
{
    public static function registerDomainFolders()
    {
        static::register(base_path('src/Support'), 'Support');

        $folders = static::directories(base_path('src/Support'));

        foreach ($folders as $folder) {
            static::register($folder, 'App\\' . basename($folder));
        }
    }

    /**
     * Automagically registers all the Blade components in the DDD structure
     *
     * @param array|string|null $directory Optional directories. Uses base_path('src/Support') by default.
     * @param string $namespace Optional namespace to filter classes.
     * @return void
     */
    public static function register($directory = null, $namespace = null)
    {
        $directory = $directory ?? base_path('src/Support');
        $namespace = $namespace ?? 'Support';

        $directories = is_array($directory) ? $directory : [$directory];

        foreach ($directories as $directory) {

            $classes = static::files($directory, function ($file) use ($directory, $namespace) {
                $pathname = $file->getPathname();
                $path = dirname($pathname) . DIRECTORY_SEPARATOR . basename($pathname, '.' . pathinfo($pathname, PATHINFO_EXTENSION));
                $class = $namespace . '\\' . substr($path, strlen($directory) + 1);
                $class = str_replace('/', '\\', $class);
                return $class;
            });

            foreach ($classes as $class) {
                if (is_subclass_of($class, Component::class)) {
                    BladeFacade::component($class);
                }
            }
        }
    }

    protected static function files($directory, $filterCallback = null, $mapperCallback = null)
    {
        $results = [];

        foreach (new DirectoryIterator($directory) as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isDir()) {
                $results = array_merge($results, static::files($file->getPathname(), $filterCallback, $mapperCallback));
            } else {
                if (!is_callable($filterCallback) || $filterCallback($file)) {
                    $results[] = is_callable($mapperCallback) ? $mapperCallback($file) : $file->getPathname();
                }
            }
        }

        return $results;
    }

    protected static function directories($directory)
    {
        $results = [];

        foreach (new DirectoryIterator($directory) as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isDir()) {
                $results[] = $file->getPathname();
            }
        }

        return $results;
    }
}
