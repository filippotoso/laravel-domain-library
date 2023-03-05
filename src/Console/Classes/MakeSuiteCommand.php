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
                            {--domain= : The domain name (ie. Invoices)}
                            {--application= : The name of the application (ie. Admin\Invoices)}
                            {--model= : The model name (ie. Invoice)}
                            {--states= : Comma separated states (ie. Paid,Pending,Overdue,Cancelled)}
                            {--events= : Comma separated events (ie. saving,created,deleting)}
                            {--dtos= : Comma separated data transfer objects (ie. Invoice,CreateInvoice)}
                            {--exceptions= : Comma separated exceptions (ie. InvalidInvoice)}
                            {--queries= : Comma separated queries (ie. InvoiceIndex)}
                            {--requests= : Comma separated form requests (ie. Invoice)}
                            {--viewmodels= : Comma separated view models (ie. InvoiceForm)}
                            {--actions= : Comma separated actions (ie. CreateInvoice,PayInvoice,CancelInvoice)}
                            {--force : Overwrite the existing classes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a model, query builder, model collection, data transfer object and states';


    protected $requirements = [
        \Spatie\LaravelData\Data::class,
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
            'domain' => $this->option('domain'),
            'application' => $this->option('application'),
            'model' => $this->option('model'),
            'dtos' => explode(',', $this->option('dtos')),
            'states' => $this->option('states'),
            'events' => $this->option('events'),
            'requests' => explode(',', $this->option('requests')),
            'dtos' => explode(',', $this->option('dtos')),
            'exceptions' => explode(',', $this->option('exceptions')),
            'queries' => explode(',', $this->option('queries')),
            'viewmodels' => explode(',', $this->option('viewmodels')),
            'actions' => explode(',', $this->option('actions')),
            'force' => $this->option('force'),
        ];

        if ($data['model'] && $data['domain']) {
            $this->call('domain:make:collection', [
                '--model' => $data['model'],
                '--domain' => $data['domain'],
                '--force' => $data['force'],
            ]);

            $this->call('domain:make:model', [
                'name' => $data['model'],
                '--domain' => $data['domain'],
                '--force' => $data['force'],
            ]);

            $this->call('domain:make:querybuilder', [
                '--model' => $data['model'],
                '--domain' => $data['domain'],
                '--force' => $data['force'],
            ]);
        }

        if ($data['domain'] && $data['application']) {
            foreach ($data['dtos'] as $dto) {
                if ($dto) {
                    $this->call('domain:make:dto', [
                        'name' => $dto,
                        '--domain' => $data['domain'],
                        '--application' => $data['application'],
                        '--force' => $data['force'],
                    ]);
                }
            }
        }

        if ($data['domain']) {
            foreach ($data['exceptions'] as $exception) {
                if ($exception) {
                    $this->call('domain:make:exception', [
                        'name' => $exception,
                        '--domain' => $data['domain'],
                        '--force' => $data['force'],
                    ]);
                }
            }
        }

        if ($data['domain'] && $data['application'] && $data['model']) {
            foreach ($data['queries'] as $query) {
                if ($query) {
                    $this->call('domain:make:query', [
                        'name' => $query,
                        '--domain' => $data['domain'],
                        '--application' => $data['application'],
                        '--model' => $data['model'],
                        '--force' => $data['force'],
                    ]);
                }
            }

            foreach ($data['viewmodels'] as $viewmodel) {
                if ($viewmodel) {
                    $this->call('domain:make:viewmodel', [
                        'name' => $viewmodel,
                        '--domain' => $data['domain'],
                        '--application' => $data['application'],
                        '--model' => $data['model'],
                        '--force' => $data['force'],
                    ]);
                }
            }

            foreach ($data['actions'] as $action) {
                if ($action) {
                    $this->call('domain:make:action', [
                        'name' => $action,
                        '--domain' => $data['domain'],
                        '--application' => $data['application'],
                        '--model' => $data['model'],
                        '--force' => $data['force'],
                    ]);
                }
            }
        }

        if ($data['application']) {
            foreach ($data['requests'] as $request) {
                if ($request) {
                    $this->call('domain:make:request', [
                        'name' => $request,
                        '--application' => $data['application'],
                        '--force' => $data['force'],
                    ]);
                }
            }
        }

        if ($data['model'] && $data['domain'] && $data['states']) {
            $this->call('domain:make:states', [
                '--model' => $data['model'],
                '--domain' => $data['domain'],
                '--states' => $data['states'],
                '--force' => $data['force'],
            ]);
        }

        if ($data['model'] && $data['domain'] && $data['events']) {
            $this->call('domain:make:subscriber', [
                '--model' => $data['model'],
                '--domain' => $data['domain'],
                '--events' => $data['events'],
                '--force' => $data['force'],
            ]);
        }
    }
}
