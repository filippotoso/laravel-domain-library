<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeQueryBuilderCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:querybuilder 
                            {name : The name of the view model (ie. Invoice)}                        
                            {domain : The domain name (ie. Invoices)}
                            {--force : Overwrite the existing query builder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a query builder';

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
            'domain' => $this->argument('domain'),
        ];

        $this->checkExisting($data);

        $this->storeStub('querybuilder', $data);
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/QueryBuilders/' . $data['name'] . 'QueryBuilder.php');
    }
}
