<?php

namespace Happytodev\Cyclone\Commands;

use Tempest\Console\ConsoleCommand;

final readonly class Cyclone
{
    /**
     * Copy every assets from cyclone to the final site
     *
     * @return void
     */
    #[ConsoleCommand]
    public function assets(): void
    {
        // List of files to be copied with their sources and destinations
        $filesToCopy = [
            [
                'source' => './vendor/happytodev/cyclone/src/Resources/img/logo.webp',
                'destination' => './public/img/logo.webp'
            ],
            [
                'source' => './vendor/happytodev/cyclone/src/Resources/main.entrypoint.css',
                'destination' => './app/main.entrypoint.css'
            ],
            [
                'source' => './vendor/happytodev/cyclone/src/Resources/main.entrypoint.ts',
                'destination' => './app/main.entrypoint.ts'
            ],
        ];

        // Browse each file to be copied
        foreach ($filesToCopy as $file) {
            $source = $file['source'];
            $destination = $file['destination'];
            $destinationDir = dirname($destination);

            // Check that the destination directory exists, if not create it
            if (!is_dir($destinationDir)) {
                if (!mkdir($destinationDir, 0755, true)) {
                    echo "Error: Unable to create the directory $destinationDir.\n";
                    continue; // Goes to the next file in the event of an error
                }
            }

            // Check if the source file exists
            if (file_exists($source)) {
                if (copy($source, $destination)) {
                    echo "The file " . basename($source) . " was successfully copied to $destinationDir.\n";
                } else {
                    echo "Error: Unable to copy file " . basename($source) . ".\n";
                }
            } else {
                echo "Error: The source file $source does not exist.\n";
            }
        }
    }

    #[ConsoleCommand]
    public function info(): void {
        echo "Cyclone v1.0.0-alpha.1\n";
    }
}
