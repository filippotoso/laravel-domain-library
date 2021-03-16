<?php

namespace FilippoToso\Domain\Console\Traits;

trait Stubbalbe
{
    protected function checkRequirement()
    {
        foreach ($this->requirements as $requirement) {
            if (!class_exists($requirement)) {
                throw new \Exception('The class ' . $requirement . ' is required to create a view model!');
            }
        }
    }

    protected function alreadyExists($data)
    {
        $path = $this->path($data);

        if (file_exists($path) && !$this->option('force')) {
            return true;
        }

        return false;
    }

    protected function parseStub($stub, $data)
    {
        $path = base_path('stubs/laravel-domain-library/' . $stub . '.stub');
        $path = file_exists($path) ? $path : __DIR__ . '/../../../stubs/' . $stub . '.stub';

        $content = file_get_contents($path);

        $data['object'] = lcfirst($data['model'] ?? null);

        $search = [];
        $replace = [];
        foreach ($data as $key => $value) {
            $search[] = '{{ ' . $key . ' }}';
            $replace[] = $value;
        }
        $content = str_replace($search, $replace, $content);

        return $content;
    }

    protected function storeStub($stub, $data, $path = null)
    {
        $path = $path ?? $this->path($data);
        $content = $this->parseStub($stub, $data);
        $this->makeDirectory(dirname($path));
        file_put_contents($path, $content);
    }
}
