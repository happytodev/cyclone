<?php

namespace Happytodev\Cyclone\Commands;

use App\Auth\User;
use DateTimeImmutable;
use Tempest\Console\Schedule;
use function Tempest\root_path;
use Tempest\Console\HasConsole;
use Symfony\Component\Yaml\Yaml;
use Happytodev\Cyclone\Models\Post;
use Tempest\Console\ConsoleCommand;
use Tempest\Validation\Rules\Email;

use function Tempest\Database\Query;
use Tempest\Console\Scheduler\Every;
use Tempest\Validation\Rules\Length;
use function Tempest\Support\Arr\every;
use Happytodev\Cyclone\Repositories\PostRepository;

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

        $user = User::select()->first();

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

    /**
     * Add a default user. Using it in install process
     *
     * @return void
     */
    #[ConsoleCommand('cyclone:add-default-user')]
    public function addDefaultUser(): void
    {

        $user = new User(
            name: 'John Doe',
            email: 'jdoe@gmail.com',
        )
            ->setPassword('password')
            ->save()
            ->grantPermission('admin');
    }

    #[ConsoleCommand('cyclone:add-user')]
    public function adduser(): void
    {

        $name = $this->console->ask('What should be the name?', validation: [new Length(min: 3)]);
        $email = $this->console->ask('What should be the email?', validation: [new Email()]);
        $password = $this->console->ask('What should be the password?', validation: [new Length(min: 8)]);

        $user = new User(
            name: $name,
            email: $email,
        )
            ->setPassword($password)
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
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/src/Resources/vite.config.ts.stub',
                'destination' => './vite.config.ts'
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
        echo "Cyclone v1.0.0-alpha.15\n";
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

            $user = User::select()
                ->where('id == ?', $frontmatter['user_id'])
                ->first();

            $postData = [
                'slug' => $frontmatter['slug'],
                'title' => $frontmatter['title'],
                'tldr' => $frontmatter['tldr'],
                'user' => $user,
                'markdown_file_path' => $file,
                'created_at' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $frontmatter['created_at']),
                'published_at' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $frontmatter['published_at']),
                'cover_image' => $frontmatter['cover_image'] ?? '',
                'published' => (bool) $frontmatter['published'] ?? false,
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
        $this->console->info('Starting Cyclone CMS installation...');

        // Step 1: Install Tempest framework
        $this->runCommand('./vendor/bin/tempest install framework --no-interaction', 'Error installing Tempest framework.');

        // Step 2: Create .gitignore file
        $this->runCommand('touch .gitignore', 'Error creating .gitignore file.');

        // Step 3: Create package.json skeleton
        $this->runCommand('npm init --yes', 'Error creating package.json.');

        // Step 4: Install Vite with Tailwind and npm
        $this->runCommand('php tempest install vite --tailwind --no-interaction', 'Error installing Vite with Tailwind.');

        // Step 5: Install authentication module
        $this->runCommand('php tempest install auth --no-interaction', 'Error installing authentication module.');

        // Step 6: Run migrations
        $this->runCommand('php tempest migrate:up', 'Error running migrations.');

        // Step 7: Add a default user
        $this->runCommand('php tempest cyclone:add-default-user', 'Error adding default user.');

        // Step 8: Add a blog post
        $this->runCommand('php tempest cyclone:add-blog-post', 'Error adding blog post.');

        // Step 9: Copy assets
        $this->runCommand('php tempest cyclone:assets', 'Error copying assets.');

        // Step 10: Sync posts
        $this->runCommand('php tempest cyclone:sync-posts', 'Error syncing posts.');

        // Step 11: Install Tailwind Typography dependencies
        $this->runCommand('npm install -D @tailwindcss/typography', 'Error installing @tailwindcss/typography.');

        // Step 12: Install npm dependencies
        $this->runCommand('npm install', 'Error installing npm dependencies.');

        // Step 13: Run dev mode
        $this->runCommand('npm run dev -- --no-open', 'Error running npm run dev.');

        $this->success('Cyclone CMS installed successfully!');

        $this->info('Now you have to create the first user by launching the command: php tempest cyclone:add-user');
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
        $this->console->info("Executing: {$command}");
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            $this->console->error($errorMessage);
            exit(1);
        }
    }
}
