<?php

namespace FilippoToso\Domain\Support;

use DirectoryIterator;
use Illuminate\Support\Facades\Blade as BladeFacade;
use Illuminate\View\Component;

class Blade
{
    public static function registerDomainFolders()
    {
        static::register(base_path('src/Support/View/Components'), 'Support/View/Components');

        $folders = static::directories(base_path('src/App'));

        foreach ($folders as $folder) {
            static::register($folder . '/View/Components', 'App\\' . basename($folder) . '\\View\\Components');
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
        $directory = $directory ?? base_path('src/Support/View/Components');
        $namespace = $namespace ?? 'Support';

        $directories = is_array($directory) ? $directory : [$directory];

        foreach ($directories as $directory) {

            if (!is_dir($directory)) {
                continue;
            }

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

    protected static function files($directory, $mapperCallback = null)
    {
        $results = [];

        foreach (new DirectoryIterator($directory) as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isDir()) {
                $results = array_merge($results, static::files($file->getPathname(), $mapperCallback));
            } else {
                $results[] = is_callable($mapperCallback) ? $mapperCallback($file) : $file->getPathname();
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
