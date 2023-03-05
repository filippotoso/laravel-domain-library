<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeDataCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:dto 
                            {name : The name of the data transfer object (ie. Invoice)}
                            {--domain= : The domain name (ie. Invoices)}
                            {--application= : The name of the application (ie. Admin\Invoices)}
                            {--force : Overwrite the existing data transfer object}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a data transfer object';

    protected $requirements = [\Spatie\LaravelData\Data::class];

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
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making data transfer object %sData...', $data['name']));
            $this->storeStub('data', $data);
            $this->info(sprintf('Data transfer object %sData successfully made!', $data['name']));
        } else {
            $this->error(sprintf('Data transfer object %sData already exists!', $data['name']));
        }
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/Data/' . $data['name'] . 'Data.php');
    }
}
