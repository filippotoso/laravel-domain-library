<?php

namespace FilippoToso\Domain\Console;

use FilippoToso\Domain\Console\Traits\Makable;
use Illuminate\Console\Command;

class MakeDomainCommand extends Command
{
    use Makable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:domain 
                            {domain : The domain name (ie. Invoices)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a domain folder structure';

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
        $domain = $this->argument('domain');

        $folders = [
            'Actions', 'QueryBuilders', 'Collections', 'DataTransferObjects',
            'Events', 'Exceptions', 'Listeners', 'Models', 'Rules',
            'States', 'Observers', 'Subscribers',
        ];

        $this->info(sprintf('Making domain %s...', $domain));

        foreach ($folders as $folder) {
            $path = base_path('src/Domain/' . $domain . '/' . $folder);
            $this->makeDirectory($path);
        }
    }
}
