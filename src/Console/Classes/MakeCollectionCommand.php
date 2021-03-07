<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeCollectionCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:collection 
                            {name : The name of the collection (ie. Invoice)}                        
                            {domain : The domain name (ie. Invoices)}
                            {model : The model name (ie. Invoice)}
                            {--force : Overwrite the existing collection}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a model collection';

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
            'model' => $this->argument('model'),
        ];

        $this->checkExisting($data);

        $this->storeStub('collection', $data);
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/Collections/' . $data['name'] . 'Collection.php');
    }
}
