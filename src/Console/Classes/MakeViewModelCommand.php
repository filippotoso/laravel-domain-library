<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeViewModelCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:viewmodel 
                            {name : The name of the view model (ie. InvoiceForm)}                        
                            {--domain= : The domain name (ie. Invoices)}
                            {--application= : The name of the application (ie. Admin\Invoices)}
                            {--model= : The name of the model (ie. Invoice)}
                            {--force : Overwrite the existing view model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a view model';

    protected $requirements = [\Spatie\ViewModels\ViewModel::class];

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
            $this->info(sprintf('Making view model %sViewModel...', $data['name']));
            $this->storeStub('viewmodel', $data);
            $this->info(sprintf('View model %sViewModel successfully made!', $data['name']));
        } else {
            $this->error(sprintf('View model %sViewModel already exists!', $data['name']));
        }
    }

    protected function path($data)
    {
        return base_path('src/App/' . str_replace('\\', '/', $data['application']) . '/ViewModels/' . $data['name'] . 'ViewModel.php');
    }
}
