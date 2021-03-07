<?php

namespace FilippoToso\Domain\Console\Traits;

use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use League\Flysystem\Adapter\Local;

trait Makable
{
    protected function makeDirectory($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
    }

    protected function copyDirectory($from, $to)
    {
        $this->copyManagedFiles(new MountManager([
            'from' => new Filesystem(new Local($from)),
            'to' => new Filesystem(new Local($to)),
        ]));
    }

    /**
     * Move all the files in the given MountManager.
     *
     * @param  \League\Filesystem\MountManager  $manager
     * @return void
     */
    protected function copyManagedFiles($manager)
    {
        foreach ($manager->listContents('from://', true) as $file) {
            if ($file['type'] === 'file') {
                $manager->put('to://' . $file['path'], $manager->read('from://' . $file['path']));
            }
        }
    }
}
