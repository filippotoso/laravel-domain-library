<?php

namespace FilippoToso\Domain\Support;

use FilippoToso\Domain\Console\MakeApplication;
use FilippoToso\Domain\Console\MakeDomain;
use FilippoToso\Domain\Console\SetupStructure;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class ServiceProvider extends EventServiceProvider
{

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupStructure::class,
                MakeDomain::class,
                MakeApplication::class,
            ]);
        }
    }
}
