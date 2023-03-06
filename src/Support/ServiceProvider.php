<?php

namespace FilippoToso\Domain\Support;

use FilippoToso\Domain\Console;
use FilippoToso\Domain\Console\Classes;
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

        $this->publishes([
            __DIR__ . '/../../stubs/' => base_path('stubs/laravel-domain-library')
        ], 'stubs');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\SetupStructureCommand::class,

                Console\MakeDomainCommand::class,
                Console\MakeApplicationCommand::class,

                Classes\MakeViewModelCommand::class,
                Classes\MakeQueryBuilderCommand::class,
                Classes\MakeQueryCommand::class,
                Classes\MakeDataCommand::class,
                Classes\MakeActionCommand::class,
                Classes\MakeModelCommand::class,
                Classes\MakeSuiteCommand::class,
                Classes\MakeCollectionCommand::class,
                Classes\MakeRequestCommand::class,
                Classes\MakeStatesCommand::class,
                Classes\MakeEventsCommand::class,
                Classes\MakeSubscriberCommand::class,
                Classes\MakeExceptionCommand::class,
                Classes\MakeRoutesCommand::class,
                Classes\MakeControllerCommand::class,
            ]);
        } else {
            Blade::registerDomainFolders();
        }
    }
}
