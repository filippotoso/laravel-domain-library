<?php

namespace FilippoToso\Domain\Console;

use FilippoToso\Domain\Console\Traits\Copyable;
use Illuminate\Console\Command;

class MakeApplication extends Command
{
    use Copyable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:application 
                            {application : The application name as namespace (ie. Admin\Invoices)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make an application folder structure';

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
        $application = $this->argument('application');

        $folders = [
            'Controllers', 'Middlewares', 'Requests', 'Resources', 'ViewModels',
        ];

        foreach ($folders as $folder) {
            $path = base_path('src/App/' . $application . '/' . $folder);
            $this->makeDirectory($path);
        }
    }
}
