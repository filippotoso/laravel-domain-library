<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeActionCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:action {name : The name of the action (ie. InvoiceIndex)}                        
                            {--domain= : The domain name (ie. Invoices)}
                            {--application= : The name of the application (ie. Admin\Invoices)}
                            {--model= : The name of the model (ie. Invoice)}
                            {--force : Overwrite the existing query}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make an action';

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
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making action %s...', $data['name']));
            $this->storeStub('action', $data);
            $this->info(sprintf('Action %s successfully made!', $data['name']));
        } else {
            $this->error(sprintf('Action %s already exists!', $data['name']));
        }
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/Actions/' . $data['model'] . '/' . $data['name'] . 'Action.php');
    }
}
