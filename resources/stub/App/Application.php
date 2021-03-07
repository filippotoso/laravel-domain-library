<?php

namespace App;

class Application extends \Illuminate\Foundation\Application
{
    protected $namespace = 'App\\';

    public function __construct($basePath = null)
    {
        parent::__construct($basePath);

        $this->useAppPath('src/App');
    }
}
