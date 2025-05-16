<?php

namespace App\Commands;

use App\Auth\User;
use DateTimeImmutable;
use App\models\Post;
use Tempest\Console\ConsoleCommand;
use App\Repositories\PostRepository;

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
                'source' => './app/Resources/img/logo.webp',
                'destination' => './public/img/logo.webp'
            ],
            [
                'source' => './app/Resources/main.entrypoint.css',
                'destination' => './app/main.entrypoint.css'
            ],
            [
                'source' => './app/Resources/main.entrypoint.ts',
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
    public function info(): void
    {
        echo "Cyclone v1.0.0-alpha.1\n";
    }

    #[ConsoleCommand]
    public function adduser(): void
    {
        $user = new User(
            name: 'Happy',
            email: 'happytodev@gmail.com',
        )
            ->setPassword('password')
            ->save()
            ->grantPermission('admin');
    }

    #[ConsoleCommand]
    public function addblogpost()
    {
        $repository = new PostRepository();
        
        // $postCounter = $repository->getTotalPosts() + 1;

        $user = User::select()
            ->where('email == ?', 'happytodev@gmail.com')
            ->first();

        for ($i=0; $i < 30; $i++) { 
            $post = Post::create(
                title: 'Lorem ipsum ' . $i,
                slug: 'lorem-ipsum-' . $i,
                tldr: 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.',
                markdown_file_path: 'lorem.md',
                user: $user,
                created_at: new DateTimeImmutable(),
                published_at: new DateTimeImmutable()
            );
        }
    }
}
