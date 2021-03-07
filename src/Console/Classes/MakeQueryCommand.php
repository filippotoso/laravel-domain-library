<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeQueryCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:query 
                            {name : The name of the query (ie. InvoiceIndex)}                        
                            {domain : The domain name (ie. Invoices)}
                            {application : The name of the application (ie. Admin\Invoices)}
                            {model : The name of the model (ie. Invoice)}
                            {--force : Overwrite the existing query}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a query';

    protected $requirements = [\Spatie\QueryBuilder\QueryBuilder::class];

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
            'domain' => $this->argument('domain'),
            'application' => $this->argument('application'),
            'model' => $this->argument('model'),
        ];

        $this->checkExisting($data);

        $this->storeStub('query', $data);
    }

    protected function path($data)
    {
        return base_path('src/App/' . str_replace('\\', '/', $data['application']) . '/Queries/' . $data['name'] . 'Query.php');
    }
}
