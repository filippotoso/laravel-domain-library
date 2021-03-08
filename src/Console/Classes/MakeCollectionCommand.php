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
                            {--model= : The model name (ie. Invoice)}
                            {--domain= : The domain name (ie. Invoices)}
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
            'model' => $this->option('model'),
            'domain' => $this->option('domain'),
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making collection %sCollection...', $data['model']));
            $this->storeStub('collection', $data);
            $this->info(sprintf('Action %sCollection successfully made!', $data['model']));
        } else {
            $this->error(sprintf('Action %sCollection already exists!', $data['model']));
        }
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/Collections/' . $data['model'] . 'Collection.php');
    }
}
