<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeModelCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:model 
                            {name : The name of the model (ie. Invoice)}                        
                            {--domain= : The domain name (ie. Invoices)}
                            {--force : Overwrite the existing collection}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a model';

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
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making model %s...', $data['name']));
            $this->storeStub('model', $data);
            $this->info(sprintf('Model %s successfully made!', $data['name']));
        } else {
            $this->error(sprintf('Model %s already exists!', $data['name']));
        }
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/Models/' . $data['name'] . '.php');
    }
}
