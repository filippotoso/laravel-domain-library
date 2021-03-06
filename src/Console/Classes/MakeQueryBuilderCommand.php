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
                            {--model= : The name of the model (ie. Invoice)}                        
                            {--domain= : The domain name (ie. Invoices)}
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
            'model' => $this->option('model'),
            'domain' => $this->option('domain'),
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making query builder %sQueryBuilder...', $data['model']));
            $this->storeStub('querybuilder', $data);
            $this->info(sprintf('Query builder %sQueryBuilder successfully made!', $data['model']));
        } else {
            $this->error(sprintf('Query builder %sQueryBuilder already exists!', $data['model']));
        }
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/QueryBuilders/' . $data['model'] . 'QueryBuilder.php');
    }
}
