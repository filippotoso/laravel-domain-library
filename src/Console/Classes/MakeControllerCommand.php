<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeControllerCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:controller {name : The name of the controller (ie. Invoice)}                        
                            {--domain= : The domain name (ie. Invoices)}
                            {--application= : The name of the application (ie. Admin\Invoices)}
                            {--model= : The name of the model (ie. Invoice)}
                            {--route= : The name of the route (ie. admin.invoices)}
                            {--view= : The path of the view (ie. admin.invoices)}
                            {--url= : The base url (ie. admin/invoices)}
                            {--suite : Create a suite of classes (request, dtos, actions, etc.)}
                            {--force : Overwrite the existing action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a controller';

    protected $requirements = [];

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
            'name' => $this->argument('name'),
            'domain' => $this->option('domain'),
            'application' => $this->option('application'),
            'model' => $this->option('model'),
            'route' => $this->option('route'),
            'url' => $this->option('url') ?? str_replace('.', '/', $this->option('route')),
            'view' => $this->option('view') ?? $this->option('route'),
            'force' => $this->option('force'),
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making controller %s...', $data['name']));
            $this->storeStub('controller', $data);
            $this->info(sprintf('Controller %s successfully made!', $data['name']));
        } else {
            $this->error(sprintf('Controller %s already exists!', $data['name']));
        }

        if ($this->option('suite')) {

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

            $this->call('domain:make:dto', [
                'name' => $data['model'],
                '--domain' => $data['domain'],
                '--application' => $data['application'],
                '--force' => $data['force'],
            ]);
            $this->call('domain:make:query', [
                'name' => $data['model'] . 'Index',
                '--domain' => $data['domain'],
                '--application' => $data['application'],
                '--model' => $data['model'],
                '--force' => $data['force'],
            ]);

            $this->call('domain:make:viewmodel', [
                'name' => $data['model'] . 'Form',
                '--domain' => $data['domain'],
                '--application' => $data['application'],
                '--model' => $data['model'],
                '--force' => $data['force'],
            ]);

            $this->call('domain:make:viewmodel', [
                'name' => $data['model'] . 'Index',
                '--domain' => $data['domain'],
                '--application' => $data['application'],
                '--model' => $data['model'],
                '--force' => $data['force'],
            ]);

            $this->call('domain:make:action', [
                'name' => 'Create' . $data['model'],
                '--domain' => $data['domain'],
                '--application' => $data['application'],
                '--model' => $data['model'],
                '--force' => $data['force'],
            ]);

            $this->call('domain:make:action', [
                'name' => 'Update' . $data['model'],
                '--domain' => $data['domain'],
                '--application' => $data['application'],
                '--model' => $data['model'],
                '--force' => $data['force'],
            ]);

            $this->call('domain:make:request', [
                'name' => $data['model'],
                '--application' => $data['application'],
                '--force' => $data['force'],
            ]);

            $this->call('domain:make:routes', [
                'name' => $data['route'],
                '--url' => $data['url'],
                '--application' => $data['application'],
                '--controller' => $data['name'],
                '--model' => $data['model'],
                '--force' => $data['force'],
            ]);
        }
    }

    protected function path($data)
    {
        return base_path('src/App/' . str_replace('\\', '/', $data['application']) . '/Controllers/' . $data['name'] . 'Controller.php');
    }
}
