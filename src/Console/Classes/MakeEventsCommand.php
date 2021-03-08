<?php

namespace FilippoToso\Domain\Console\Classes;

use FilippoToso\Domain\Console\Traits\Makable;
use FilippoToso\Domain\Console\Traits\Stubbalbe;
use Illuminate\Console\Command;

class MakeEventsCommand extends Command
{
    use Makable, Stubbalbe;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:events 
                            {--model= : The name of the model (ie. Invoice)}                        
                            {--domain= : The domain name (ie. Invoices)}
                            {--events= : Comma separated events (ie. saving,created,deleting)}
                            {--force : Overwrite the existing events}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a set of model events';

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

        $events = explode(',', strtolower($this->option('events')));
        foreach ($events as $event) {
            $event = ucfirst($event);
            $path = base_path('src/Domain/' . str_replace('\\', '/', $data['domain']) . '/Events/' . $data['model'] . '/' . $data['model'] . $event . 'Event.php');

            if (file_exists($path) && !$this->option('force')) {
                $this->error(sprintf('Event %s%sEvent already exists!', $data['model'], $event));
            } else {
                $this->info(sprintf('Making event %s%sEvent...', $data['model'], $event));
                $this->storeStub('event', array_merge($data, ['event' => $event]), $path);
                $this->info(sprintf('Event %s%sEvent successfully made!', $data['model'], $event));
            }
        }
    }
}
