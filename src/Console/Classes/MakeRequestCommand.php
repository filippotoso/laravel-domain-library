<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeRequestCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:request 
                            {name : The name of the query (ie. Invoice)}                        
                            {--application= : The name of the application (ie. Admin\Invoices)}
                            {--force : Overwrite the existing request}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a form request';

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
            'application' => $this->option('application'),
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making request %sFormRequest...', $data['name']));
            $this->storeStub('request', $data);
            $this->info(sprintf('Request %sFormRequest successfully made!', $data['name']));
        } else {
            $this->error(sprintf('Request %sFormRequest already exists!', $data['name']));
        }
    }

    protected function path($data)
    {
        return base_path('src/App/' . str_replace('\\', '/', $data['application']) . '/Requests/' . $data['name'] . 'FormRequest.php');
    }
}
