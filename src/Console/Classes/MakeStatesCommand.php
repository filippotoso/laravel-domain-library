<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeStatesCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:states 
                            {name : The name of the state (ie. Invoice)}                        
                            {domain : The domain name (ie. Invoices)}
                            {states : Comma separated states (ie. Paid,Pending,Overdue,Cancelled)}
                            {--force : Overwrite the existing state}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a set of states';

    protected $requirements = [\Spatie\ModelStates\State::class];

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

        $this->storeStub('abstractstate', $data);

        $states = explode(',', $this->argument('states'));
        foreach ($states as $state) {
            $path = base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/States/' . $data['name'] . '/' . $state . '.php');
            $this->storeStub('state', array_merge($data, ['state' => $state]), $path);
        }
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/States/' . $data['name'] . 'State.php');
    }
}
