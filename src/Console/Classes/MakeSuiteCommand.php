<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeSuiteCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:suite 
                            {domain : The domain name (ie. Invoices)}
                            {application : The name of the application (ie. Admin\Invoices)}
                            {model : The model name (ie. Invoice)}
                            {--force : Overwrite the existing classes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a model, query builder, model collection and data transfer object';


    protected $requirements = [
        \Spatie\DataTransferObject\DataTransferObject::class,
        \Spatie\ViewModels\ViewModel::class,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->checkRequirement();

        $data = [
            'domain' => $this->argument('domain'),
            'application' => $this->argument('application'),
            'model' => $this->argument('model'),
        ];

        $this->call('domain:make:model', [
            'name' => $data['model'],
            'domain' => $data['domain'],
            'model' => $data['model'],
            '--force' => $this->option('force'),
        ]);

        $this->call('domain:make:querybuilder', [
            'name' => $data['model'],
            'domain' => $data['domain'],
            '--force' => $this->option('force'),
        ]);

        $this->call('domain:make:collection', [
            'name' => $data['model'],
            'domain' => $data['domain'],
            'model' => $data['model'],
            '--force' => $this->option('force'),
        ]);

        $this->call('domain:make:dto', [
            'name' => $data['model'],
            'domain' => $data['domain'],
            'application' => $data['application'],
            'model' => $data['model'],
            '--force' => $this->option('force'),
        ]);

        $this->call('domain:make:request', [
            'name' => $data['model'],
            'application' => $data['application'],
            '--force' => $this->option('force'),
        ]);
    }
}
