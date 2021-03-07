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

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\SetupStructureCommand::class,

                Console\MakeDomainCommand::class,
                Console\MakeApplicationCommand::class,

                Classes\MakeViewModelCommand::class,
                Classes\MakeQueryBuilderCommand::class,
                Classes\MakeQueryCommand::class,
                Classes\MakeDataTransferObjectCommand::class,
                Classes\MakeActionCommand::class,
                Classes\MakeModelCommand::class,
                Classes\MakeSuiteCommand::class,
                Classes\MakeCollectionCommand::class,
                Classes\MakeRequestCommand::class,
                Classes\MakeStatesCommand::class,
            ]);
        }
    }
}
