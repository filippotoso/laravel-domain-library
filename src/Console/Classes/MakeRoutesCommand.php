<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeRoutesCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:routes {name : The name of the routes (ie. admin.invoices)}                        
                            {--url= : The base url (ie. admin/invoices)}
                            {--application= : The name of the application (ie. Admin\Invoices)}
                            {--controller= : The name of the controller (ie. Invoice)}
                            {--force : Overwrite the existing action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make routes';

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
            'url' => $this->option('url'),
            'application' => $this->option('application'),
            'controller' => $this->option('controller'),
        ];

        $this->info(sprintf('Making routes %s...', $data['name']));
        $this->store('routes', $data);
        $this->info(sprintf('Routes %s successfully added!', $data['name']));
    }

    protected function store($stub, $data)
    {
        $content = $this->parseStub($stub, $data);
        $path = base_path('routes/web.php');
        file_put_contents($path, $content, FILE_APPEND);
    }
}
