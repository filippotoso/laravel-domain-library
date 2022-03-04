<?php

namespace FilippoToso\Domain\Console\Traits;

use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use League\Flysystem\Local\LocalFilesystemAdapter;

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
            'from' => new Filesystem(new LocalFilesystemAdapter($from)),
            'to' => new Filesystem(new LocalFilesystemAdapter($to)),
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
                $from = $file['path'];
                $to = str_replace('from://', 'to://', $file['path']);
                $manager->copy($from, $to);
            }
        }
    }
}
