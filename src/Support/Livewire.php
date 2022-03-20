<?php

namespace FilippoToso\Domain\Support;

use DirectoryIterator;
use Livewire\Component as BaseComponent;
use FilippoToso\Domain\Livewire\Component;
use FilippoToso\Domain\Livewire\Traits\Componentable;

use Livewire\Livewire as LivewireManager;

class Livewire
{
    /**
     * Automagically registers all the Livewire components in the DDD structure
     *
     * @param string|null $directory Optional directory. Uses base_path('src/App') by default.
     * @param string $folder Optional folder name to filter files to be processed.
     * @param string $namespace Optional namespace relative to the $directory.
     * @return void
     */
    public static function register($directory = null, $folder = 'Livewire', $namespace = 'App')
    {
        $directory = $directory ?? base_path('src/App');

        $files = static::files($directory, function ($file) use ($folder) {
            return strpos($file->getPathname(), DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR) !== false;
        });

        $classes = array_map(function ($file) use ($directory, $namespace) {
            $path = dirname($file) . DIRECTORY_SEPARATOR . basename($file, '.' . pathinfo($file, PATHINFO_EXTENSION));
            $class = $namespace . '\\' . substr($path, strlen($directory) + 1);
            $class = str_replace('/', '\\', $class);
            return $class;
        }, $files);

        foreach ($classes as $class) {
            if (!class_exists($class)) {
                continue;
            }

            if (
                is_subclass_of($class, Component::class) ||
                (is_subclass_of($class, BaseComponent::class) && static::hasTrait($class, Componentable::class))
            ) {
                LivewireManager::component($class);
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
                $results = array_merge($results, static::files($file->getPathname()));
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
