<?php

namespace FilippoToso\Domain\Console;

use FilippoToso\Domain\Console\Traits\Copyable;
use Illuminate\Console\Command;

class MakeDomain extends Command
{
    use Copyable;

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

        $path = base_path('src/Domain/' . $domain);
        $this->makeDirectory($path);

        foreach ($folders as $folder) {
            $path = base_path('src/Domain/' . $domain . '/' . $folder);
            $this->makeDirectory($path);
        }

        return 0;
    }
}
