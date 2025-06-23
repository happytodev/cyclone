<?php

namespace Happytodev\Cyclone\Commands;

use App\Auth\User;
use Happytodev\Cyclone\models\Post;
use DateTimeImmutable;
use Symfony\Component\Yaml\Yaml;
use Tempest\Console\ConsoleCommand;
use Happytodev\Cyclone\Repositories\PostRepository;
use Tempest\Console\HasConsole;
use Tempest\Console\Schedule;
use Tempest\Console\Scheduler\Every;

use function Tempest\Database\Query;
use function Tempest\Support\Arr\every;
use function Tempest\root_path;

final readonly class Cyclone
{
    use HasConsole;

    private string $postsDir;

    public function __construct()
    {
        $this->postsDir = 'content/blog';
    }

    #[ConsoleCommand('cyclone:add-blog-post')]
    public function addblogpost()
    {
        $repository = new PostRepository();

        // $postCounter = $repository->getTotalPosts() + 1;

        $user = User::select()
            ->where('email == ?', 'happytodev@gmail.com')
            ->first();

        for ($i = 0; $i < 30; $i++) {
            $post = Post::create(
                title: 'Lorem ipsum ' . $i,
                slug: 'lorem-ipsum-' . $i,
                tldr: 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.',
                markdown_file_path: 'content/blog/lorem.md',
                user: $user,
                created_at: new DateTimeImmutable(),
                published_at: new DateTimeImmutable(),
                published: true
            );
        }
    }

    #[ConsoleCommand('cyclone:add-user')]
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

    /**
     * Copy every assets from cyclone to the final site
     *
     * @return void
     */
    #[ConsoleCommand('cyclone:assets')]
    public function assets(): void
    {
        // List of files to be copied with their sources and destinations
        $filesToCopy = [
            [
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/src/Resources/img/logo.webp',
                'destination' => './public/img/logo.webp'
            ],
            [
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/src/Resources/img/blog/first-post.webp',
                'destination' => './public/img/blog/first-post.webp'
            ],
            [
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/src/Resources/main.entrypoint.css.stub',
                'destination' => './app/main.entrypoint.css'
            ],
            [
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/src/Resources/main.entrypoint.ts.stub',
                'destination' => './app/main.entrypoint.ts'
            ],
            [
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/content/blog/cyclone-install.md',
                'destination' => './content/blog/cyclone-install.md'
            ],
            [
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/content/blog/second-post.md',
                'destination' => './content/blog/second-post.md'
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

    #[ConsoleCommand('cyclone:info')]
    public function info(): void
    {
        echo "Cyclone v1.0.0-alpha.13\n";
    }

    #[Schedule(Every::HOUR)]
    #[ConsoleCommand('cyclone:sync-posts')]
    public function syncPosts(): void
    {
        $repository = new PostRepository();

        $files = glob($this->postsDir . '/*.md');
        $existingSlugs = [];

        foreach ($files as $file) {
            $slug = pathinfo($file, PATHINFO_FILENAME);
            $content = file_get_contents($file);
            $frontmatter = $this->parseFrontmatter($content);

            $postData = [
                'slug' => $frontmatter['slug'],
                'title' => $frontmatter['title'],
                'tldr' => $frontmatter['tldr'],
                'markdown_file_path' => $file,
                'created_at' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $frontmatter['created_at']),
                'published_at' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $frontmatter['published_at']),
                'user_id' => $frontmatter['user_id'],
                'cover_image' => $frontmatter['cover_image'] ?? '',
                'published' => $frontmatter['published'] ?? false,
            ];


            // Manage category
            // $category = Category::firstOrCreate(['name' => $frontmatter['category']]);
            // $postData['category_id'] = $category->id;

            // Create or update the post
            $result = Post::updateOrCreate(
                ['slug' => $postData["slug"]],
                $postData
            );

            // Manage tags
            // $tags = explode(',', $frontmatter['tags']);
            // $tagIds = [];
            // foreach ($tags as $tagName) {
            //     $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
            //     $tagIds[] = $tag->id;
            // }
            // $post->tags()->sync($tagIds);

            $existingSlugs[] = $postData['slug'];
        }

        // Delete posts without a matching file
        $result = $repository->deletePostsWithoutMachingFile($existingSlugs);

        if ($result) {
            echo "Posts successfully deleted.";
        } else {
            echo "No deletions made.";
        }


        echo "Posts synchronized.\n";
    }

    private function parseFrontmatter(string $content): array
    {
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
            return Yaml::parse($matches[1]);
        }
        return [];
    }

    #[ConsoleCommand('cyclone:install')]
    public function __invoke(): void
    {
        $this->info('Starting Cyclone CMS installation...');

        // Step 1: Install Tempest framework
        // $this->runCommand('./vendor/bin/tempest install framework', 'Error installing Tempest framework.');

        // Step 2: Create .gitignore file
        $this->runCommand('touch .gitignore', 'Error creating .gitignore file.');

        // Step 3: Install Vite with Tailwind and npm
        $this->runCommand('php tempest install vite --tailwind --npm', 'Error installing Vite with Tailwind.');

        // Step 4: Install authentication module
        $this->runCommand('php tempest install auth', 'Error installing authentication module.');

        // Step 5: Run migrations
        $this->runCommand('php tempest migrate:up', 'Error running migrations.');

        // Step 6: Add a user
        $this->runCommand('php tempest cyclone:add-user', 'Error adding user.');

        // Step 7: Add a blog post
        $this->runCommand('php tempest cyclone:add-blog-post', 'Error adding blog post.');

        // Step 8: Copy assets
        $this->runCommand('php tempest cyclone:assets', 'Error copying assets.');

        // Step 9: Sync posts
        $this->runCommand('php tempest cyclone:sync-posts', 'Error syncing posts.');

        // Step 10: Install Tailwind Typography dependencies
        $this->runCommand('npm install -D @tailwindcss/typography', 'Error installing @tailwindcss/typography.');

        // Step 11: Install npm dependencies
        $this->runCommand('npm install', 'Error installing npm dependencies.');

        // Step 12: Run dev mode
        $this->runCommand('npm run dev -- --no-open', 'Error running npm run dev.');

        $this->success('Cyclone CMS installed successfully!');
    }

    /**
     * Executes a shell command and displays an error if it fails.
     *
     * @param string $command The command to execute
     * @param string $errorMessage The error message to display if the command fails
     * @return void
     */
    private function runCommand(string $command, string $errorMessage): void
    {
        $this->info("Executing: {$command}");
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            $this->error($errorMessage);
            exit(1);
        }
    }
}
