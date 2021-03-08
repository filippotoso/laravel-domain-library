<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeSubscriberCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:subscriber 
                            {--model= : The name of the model (ie. Invoice)}                        
                            {--domain= : The domain name (ie. Invoices)}
                            {--events= : Comma separated events (ie. saving,created,deleting)}
                            {--force : Overwrite the existing events}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a model subscriber with optional events';

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
            'events' => $this->option('events'),
        ];

        if (!$this->alreadyExists($data)) {
            $this->info(sprintf('Making subscriber %sSubscriber...', $data['model']));
            $this->storeStub('subscriber', $data);
            $this->info(sprintf('Subscriber %sSubscriber successfully made!', $data['model']));
        } else {
            $this->error(sprintf('Subscriber %sSubscriber already exists!', $data['model']));
        }

        $this->call('domain:make:events', [
            '--model' => $data['model'],
            '--domain' => $data['domain'],
            '--events' => $data['events'] ?? null,
            '--force' => $this->option('force'),
        ]);
    }

    protected function path($data)
    {
        return base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/Subscribers/' . $data['model'] . 'Subscriber.php');
    }
}
