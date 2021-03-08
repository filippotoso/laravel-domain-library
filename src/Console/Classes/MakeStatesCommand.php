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
                            {--model= : The name of the model (ie. Invoice)}                        
                            {--domain= : The domain name (ie. Invoices)}
                            {--states= : Comma separated states (ie. Paid,Pending,Overdue,Cancelled)}
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
            'model' => $this->option('model'),
            'domain' => $this->option('domain'),
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making state %sState...', $data['model']));
            $this->storeStub('abstractstate', $data);
            $this->info(sprintf('State %sState successfully made!', $data['model']));
        } else {
            $this->error(sprintf('State %sState already exists!', $data['model']));
        }

        $states = explode(',', $this->option('states'));
        foreach ($states as $state) {
            $path = base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/States/' . $data['model'] . '/' . $state . '.php');

            if (file_exists($path) && !$this->option('force')) {
                $this->error(sprintf('State %s%sState already exists!', $data['model'], $state));
            } else {
                $this->info(sprintf('Making state %s%sState...', $data['model'], $state));
                $this->storeStub('state', array_merge($data, ['state' => $state]), $path);
                $this->info(sprintf('State %s%sState successfully made!', $data['model'], $state));
            }
        }
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/States/' . $data['model'] . 'State.php');
    }
}
