<?php

namespace FilippoToso\Domain\Support;

use DirectoryIterator;
use FilippoToso\Domain\Livewire\Component;
use FilippoToso\Domain\Livewire\Traits\Componentable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Livewire\Component as BaseComponent;
use Livewire\Livewire as LivewireManager;

class Livewire
{
    protected const CACHE_KEY = __CLASS__;

    public static function clearCache()
    {
        Cache::forget(static::CACHE_KEY);
    }

    /**
     * Automagically registers all the Livewire components in the DDD structure
     *
     * @param array|string|null $directory Optional directories. Uses base_path('src/App') by default.
     * @param string $folder Optional "folder" name to filter files to be processed.
     * @param string $namespace Optional namespace to filter classes.
     * @param bool $useCache Optional cache files to speed up the process. Defaults to true in production.
     * @return void
     */
    public static function register($directory = null, $folder = 'Livewire', $namespace = 'App', $useCache = null)
    {
        $directory = $directory ?? base_path('src/App');

        $directories = is_array($directory) ? $directory : [$directory];

        $useCache = $useCache ?? App::environment('production');

        if ($useCache) {
            $classes = Cache::rememberForever(static::CACHE_KEY, function () use ($directories, $folder, $namespace) {
                return static::classes($directories, $folder, $namespace);
            });
        } else {
            $classes = static::classes($directories, $folder, $namespace);;
        }

        foreach ($classes as $class) {
            LivewireManager::component((new $class)->getName(), $class);
        }
    }

    protected static function classes($directories, $folder, $namespace)
    {
        $classes = [];

        foreach ($directories as $directory) {

            $current = static::files($directory, function ($file) use ($folder) {
                return strpos($file->getPathname(), DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR) !== false;
            }, function ($file) use ($directory, $namespace) {
                $pathname = $file->getPathname();
                $path = dirname($pathname) . DIRECTORY_SEPARATOR . basename($pathname, '.' . pathinfo($pathname, PATHINFO_EXTENSION));
                $class = $namespace . '\\' . substr($path, strlen($directory) + 1);
                $class = str_replace('/', '\\', $class);
                return $class;
            });

            $classes = array_merge($classes, $current);
        }

        $classes = array_filter($classes, function ($class) {
            return is_subclass_of($class, Component::class) ||
                (is_subclass_of($class, BaseComponent::class) && static::hasTrait($class, Componentable::class));
        });

        return $classes;
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

    protected static function hasTrait($class, $trait)
    {
        $classes = array_merge([$class], class_parents($class));

        foreach ($classes as $class) {
            $traits = class_uses($class);

            if (in_array($trait, $traits)) {
                return true;
            }
        }

        return false;
    }
}
