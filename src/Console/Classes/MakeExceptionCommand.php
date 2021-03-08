<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeExceptionCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:exception 
                            {name : The name of the exception (ie. InvalidInvoice)}                        
                            {--domain= : The domain name (ie. Invoices)}
                            {--force : Overwrite the existing exception}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a domaine exception';

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
            $this->info(sprintf('Making exception %sException...', $data['name']));
            $this->storeStub('exception', $data);
            $this->info(sprintf('Exception %sException successfully made!', $data['name']));
        } else {
            $this->error(sprintf('Exception %sException already exists!', $data['name']));
        }
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/Exceptions/' . $data['name'] . '.php');
    }
}
