<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Helpers;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class PostInstallCommand
{
    public static function postInstallHook(): void
    {
        $fileSystem = new Filesystem(
            new Local(
                __DIR__ . '/../../../'
            )
        );
        try {
            if (false === $fileSystem->has('.env')) {
                echo "Coping .env.dist to .env\n";
                $fileSystem->copy('.env.dist', '.env');

                return;
            }

            echo "File .env already exists\n";
        } catch (\Throwable $exception) {
            echo "Failed to copy .env file\n";
        }
    }
}
