<?php

namespace FilippoToso\Domain\Console;

use FilippoToso\Domain\Console\Traits\Makable;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;

class SetupStructureCommand extends Command
{
    use Makable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:setup:structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup directory structure and composer settings';

    /**
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new session table command instance.
     *
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(Composer $composer)
    {
        parent::__construct();
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->srcFolderExists()) {
            $this->error('The src folder already exists!');
        } else {
            $this->info('Creating folders...');
            $this->createFolders();

            $this->info('Copying base files...');
            $this->copyFiles();

            $this->info('Updating config/auth.php file...');
            $this->updateConfig();

            $this->info('Updating bootstrap/app.php...');
            $this->updateBootstrap();

            $this->info('Updating composer.json...');
            $this->updateComposer();

            $this->info('Dumping autoload...');
            $this->composer->dumpAutoloads();
        }
    }

    protected function srcFolderExists()
    {
        $folder = base_path('src');

        if (is_dir($folder)) {
            return true;
        }

        return false;
    }

    protected function createFolders()
    {
        $folders = [
            'src',
            'src/App',
            'src/App/Console',
            'src/App/Console/Commands',
            'src/App/Exceptions',
            'src/App/Providers',

            'src/Domain',

            'src/Support',
            'src/Support/Controllers',
            'src/Support/Models',
            'src/Support/Middleware',
            'src/Support/Helpers',
            'src/Support/Filters',
            'src/Support/Gateways', // API clients
        ];

        foreach ($folders as $folder) {
            mkdir(base_path($folder));
        }
    }

    protected function copyFiles()
    {
        $content = file_get_contents(base_path('app/Console/Kernel.php'));
        $content = str_replace([
            'namespace App\Console;',
            ' as ConsoleKernel;',
            'class Kernel',
            'extends ConsoleKernel',
            "'/Commands'",
        ], [
            'namespace App;',
            ';',
            'class ConsoleKernel',
            'extends Kernel',
            "'/Console/Commands'",
        ], $content);
        file_put_contents(base_path('src/App/ConsoleKernel.php'), $content);

        $content = file_get_contents(base_path('app/Http/Kernel.php'));
        $content = str_replace([
            'namespace App\Http;',
            'as HttpKernel;',
            'class Kernel',
            'extends HttpKernel',
            "\\App\\Http\\Middleware",
        ], [
            'namespace App;',
            ';',
            'class HttpKernel',
            'extends Kernel',
            "\\Support\\Middleware",
        ], $content);
        file_put_contents(base_path('src/App/HttpKernel.php'), $content);

        $content = file_get_contents(base_path('app/Models/User.php'));
        $content = str_replace([
            'namespace App\Models;',
        ], [
            'namespace Support\Models;',
        ], $content);
        file_put_contents(base_path('src/Support/Models/User.php'), $content);

        $content = file_get_contents(base_path('app/Http/Controllers/Controller.php'));
        $content = str_replace([
            'namespace App\Http\Controllers;',
        ], [
            'namespace Support\Controllers;',
        ], $content);
        file_put_contents(base_path('src/Support/Controllers/Controller.php'), $content);

        $this->copyDirectory(base_path('app/Exceptions'), base_path('src/App/Exceptions'));
        $this->copyDirectory(base_path('app/Providers'), base_path('src/App/Providers'));
        $this->copyDirectory(base_path('app/Http/Middleware'), base_path('src/Support/Middleware'));

        $this->copyDirectory(__DIR__ . '/../../resources/src/App', base_path('src/App'));
        $this->copyDirectory(__DIR__ . '/../../resources/src/Support', base_path('src/Support'));

        $files = glob(base_path('src/Support/Middleware/*.php'));

        foreach ($files as $file) {
            $content = file_get_contents($file);

            $content = str_replace(
                'namespace App\Http\Middleware;',
                'namespace Support\Middleware;',
                $content
            );

            file_put_contents($file, $content);
        }
    }

    protected function updateConfig()
    {
        $path = base_path('config/auth.php');

        $content = file_get_contents($path);

        $content = str_replace(
            'App\Models\User::class',
            'Support\Models\User::class',
            $content
        );

        file_put_contents($path, $content);
    }

    protected function updateBootstrap()
    {
        $path = base_path('bootstrap/app.php');

        $content = file_get_contents($path);

        $content = str_replace([
            "new Illuminate\Foundation\Application",
            'App\\Http\\Kernel::class',
            'App\\Console\\Kernel::class',
        ], [
            "new App\Application",
            'App\\HttpKernel::class',
            'App\\ConsoleKernel::class',
        ], $content);

        file_put_contents($path, $content);
    }

    protected function updateComposer()
    {
        $path = base_path('composer.json');
        $data = json_decode(file_get_contents($path), true);

        $data['autoload']['psr-4'] = [
            'App\\' => 'src/App',
            'Domain\\' => 'src/Domain',
            'Support\\' => 'src/Support',
        ];

        $data['autoload']['files'] = $data['autoload']['files'] ?? [];
        $data['autoload']['files'][] = 'src/Support/helpers.php';

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
