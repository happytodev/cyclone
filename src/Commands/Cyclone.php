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
        // List of files and directories to be copied with their sources and destinations
        $filesToCopy = [
            [
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/src/Resources/img',
                'destination' => './public/img'
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
            [
                'source' => root_path() . DIRECTORY_SEPARATOR . 'vendor/happytodev/cyclone/src/Resources/favicon',
                'destination' => './public/favicon'
            ],
        ];

        // Browse each item to be copied
        foreach ($filesToCopy as $file) {
            $source = $file['source'];
            $destination = $file['destination'];

            if (is_dir($source)) {
                // If it's a directory, copy recursively
                $this->copyDirectory($source, $destination);
                echo "The directory " . basename($source) . "has been copied to $destination.\n";
            } elseif (is_file($source)) {
                // If it's a file, check and create the destination directory
                $destinationDir = dirname($destination);
                if (!is_dir($destinationDir)) {
                    if (!mkdir($destinationDir, 0755, true)) {
                        echo "Error: Unable to create the $destinationDir directory.\n";
                        continue;
                    }
                }
                // Copy the file
                if (copy($source, $destination)) {
                    echo "The file " . basename($source) . "has been successfully copied to $destination.\n";
                } else {
                    echo "Error: Unable to copy the file " . basename($source) . ".\n";
                }
            } else {
                echo "Error: The source $source does not exist or is not a file or directory.\n";
            }
        }
    }

    /**
     * Recursively copies a directory and its contents to a destination
     */
    private function copyDirectory(string $source, string $destination): void
    {
        // Check that the source is a directory
        if (!is_dir($source)) {
            echo "Error: The source $source is not a directory.\n";
            return;
        }

        // Create the destination directory if it does not exist
        if (!is_dir($destination)) {
            if (!mkdir($destination, 0755, true)) {
                echo "Error: Unable to create the $destination directory.\n";
                return;
            }
        }

        // List items in the source directory
        $items = scandir($source);
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') {
                continue; // Ignore . and ..
            }

            $srcPath = $source . DIRECTORY_SEPARATOR . $item;
            $destPath = $destination . DIRECTORY_SEPARATOR . $item;

            if (is_dir($srcPath)) {
                // If it is a sub-directory, call recursively
                $this->copyDirectory($srcPath, $destPath);
            } elseif (is_file($srcPath)) {
                // If it's a file, copy it
                if (!copy($srcPath, $destPath)) {
                    echo "Error: Unable to copy file $srcPath to $destPath.\n";
                }
            } else {
                echo "Warning: $srcPath is neither a file nor a directory, and is ignored.\n";
            }
        }
    }


    #[ConsoleCommand('cyclone:info')]
    public function info(): void
    {
        echo "Cyclone v1.0.0-alpha\n";
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

            // Handle empty cover_image
            $coverImage = $frontmatter['cover_image'] ?? '';
            if (empty($coverImage)) {
                ll($coverImage, empty($coverImage));
                $coverImage = $this->generateCoverImage($frontmatter['title'], $slug);
                ll($coverImage);
            }

            $postData = [
                'slug' => $frontmatter['slug'],
                'title' => $frontmatter['title'],
                'tldr' => $frontmatter['tldr'],
                'user' => $user,
                'markdown_file_path' => $file,
                'created_at' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $frontmatter['created_at']),
                'published_at' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $frontmatter['published_at']),
                'cover_image' => $coverImage,
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


    /**
     * Generate a cover image with a black background and white text containing the post title.
     *
     * @param string $title The title of the post
     * @param string $slug The slug of the post for naming the image
     * @return string The relative path to the generated image or empty string on failure
     */
    private function generateCoverImage(string $title, string $slug): string
    {
        ll('Entering generateCoverImage');
        $destinationDir = './public/img/blog/';
        $imageFilename = $slug . '-cover.png';
        $imagePath = $destinationDir . $imageFilename;

        // Ensure the destination directory exists
        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0755, true)) {
                $this->console->error("Unable to create directory $destinationDir.");
                return '';
            }
        }

        // Create image: 1200x600 pixels
        $image = imagecreatetruecolor(1200, 600);
        if (!$image) {
            $this->console->error("Failed to create image for post: $title");
            return '';
        }

        // Allocate colors
        $black = imagecolorallocate($image, 0, 0, 0); // Black background
        $textColor = imagecolorallocate($image, 85, 172, 238); // Cyclone blue for text

        // Fill background
        imagefill($image, 0, 0, $black);

        // Define font path (adjust to your system's font or bundle a .ttf file)
        $fontPath = './vendor/happytodev/cyclone/src/Resources/fonts/DejaVuSans.ttf'; // Example path
        if (!file_exists($fontPath)) {
            $this->console->error("Font file not found at $fontPath");
            imagedestroy($image);
            return '';
        }

        // Wrap text to fit within image
        $fontSize = 40;
        $maxWidth = 1000; // Maximum text width
        $textBox = $this->wrapText($title, $fontSize, $fontPath, $maxWidth);

        // Calculate text position to center it
        $imageWidth = 1200;
        $imageHeight = 600;
        $textX = (int) (($imageWidth - $textBox['width']) / 2);
        $textY = (int) (($imageHeight - $textBox['height']) / 2 + $textBox['height'] - $textBox['descent']);

        // Add text to image
        foreach ($textBox['lines'] as $index => $line) {
            imagettftext(
                $image,
                $fontSize,
                0, // Angle
                $textX,
                $textY + ($index * ($fontSize + 10)), // Line spacing
                $textColor,
                $fontPath,
                $line
            );
        }

        // Save image
        if (!imagepng($image, $imagePath)) {
            ll("Failed to save image for post: $title");
            $this->console->error("Failed to save image for post: $title");
            imagedestroy($image);
            return '';
        }

        // Free memory
        imagedestroy($image);

        return $imageFilename;
    }

    /**
     * Wrap text to fit within a specified width for image rendering.
     *
     * @param string $text The text to wrap
     * @param float $fontSize The font size
     * @param string $fontPath The path to the TrueType font
     * @param int $maxWidth The maximum width in pixels
     * @return array Contains wrapped lines, total width, height, and descent
     */
    private function wrapText(string $text, float $fontSize, string $fontPath, int $maxWidth): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';
        $maxLineWidth = 0;

        foreach ($words as $word) {
            $testLine = $currentLine ? "$currentLine $word" : $word;
            $box = imagettfbbox($fontSize, 0, $fontPath, $testLine);
            $width = $box[2] - $box[0];

            if ($width <= $maxWidth) {
                $currentLine = $testLine;
            } else {
                if ($currentLine) {
                    $lines[] = $currentLine;
                    $lineBox = imagettfbbox($fontSize, 0, $fontPath, $currentLine);
                    $maxLineWidth = max($maxLineWidth, $lineBox[2] - $lineBox[0]);
                }
                $currentLine = $word;
            }
        }

        if ($currentLine) {
            $lines[] = $currentLine;
            $lineBox = imagettfbbox($fontSize, 0, $fontPath, $currentLine);
            $maxLineWidth = max($maxLineWidth, $lineBox[2] - $lineBox[0]);
        }

        // Calculate total height and descent
        $box = imagettfbbox($fontSize, 0, $fontPath, 'A');
        $lineHeight = $box[1] - $box[7]; // Ascent - descent
        $descent = -$box[7]; // Positive descent value
        $totalHeight = count($lines) * ($fontSize + 10) - 10; // Line spacing

        return [
            'lines' => $lines,
            'width' => $maxLineWidth,
            'height' => $totalHeight,
            'descent' => $descent,
        ];
    }

    #[ConsoleCommand('cyclone:install')]
    public function __invoke(): void
    {
        $this->console->info('Starting Cyclone CMS installation...');
        
        // Step 1: Install Tempest framework
        $this->console->info('➡️ Starting TempestPHP installation...');
        $this->runCommand('./vendor/bin/tempest install framework --no-interaction', '❌ Error installing Tempest framework.');
        $this->console->info('✅ TempestPHP installed!');
        $this->console->info('-----------------------------------------');
        
        // Step 2: Create .gitignore file
        $this->console->info('➡️ Creating .gitignore file ...');
        $this->runCommand('touch .gitignore', '❌ Error creating .gitignore file.');
        $this->console->info('✅ .gitignore created!');
        $this->console->info('-----------------------------------------');
        
        // Step 3: Create package.json skeleton
        $this->console->info('➡️ Creating package.json...');
        $this->runCommand('npm init --yes', '❌ Error creating package.json.');
        $this->console->info('✅ package.json created!');
        $this->console->info('-----------------------------------------');
        
        // Step 4: Install Vite with Tailwind and npm
        $this->console->info('➡️ Starting installing vite & tailwind...');
        $this->runCommand('php tempest install vite --tailwind --no-interaction', '❌ Error installing Vite with Tailwind.');
        $this->console->info('✅ vite & tailwind installed!');
        $this->console->info('-----------------------------------------');
        
        // Step 5: Install authentication module
        $this->console->info('➡️ Starting installing Auth package...');
        $this->runCommand('php tempest install auth --no-interaction', '❌ Error installing authentication module.');
        $this->console->info('✅ Auth package installed!');
        $this->console->info('-----------------------------------------');
        
        // Step 6: Run migrations
        $this->console->info('➡️ Starting database migration...');
        $this->runCommand('php tempest migrate:up', '❌ Error running migrations.');
        $this->console->info('✅ Database migrated!');
        $this->console->info('-----------------------------------------');
        
        // Step 7: Add a default user
        $this->console->info('➡️ Starting adding default user...');
        $this->runCommand('php tempest cyclone:add-default-user', '❌ Error adding default user.');
        $this->console->info('✅ Default user added!');
        $this->console->info('-----------------------------------------');
        
        // Step 8: Add a blog post
        $this->console->info('➡️ Starting adding blog content...');
        $this->runCommand('php tempest cyclone:add-blog-post', '❌ Error adding blog post.');
        $this->console->info('✅ Blog content added!');
        $this->console->info('-----------------------------------------');
        
        // Step 9: Copy assets
        $this->console->info('➡️ Starting copying assets...');
        $this->runCommand('php tempest cyclone:assets', '❌ Error copying assets.');
        $this->console->info('✅ Assets copied!');
        $this->console->info('-----------------------------------------');
        
        // Step 10: Sync posts
        $this->console->info('➡️ Starting posts synchronisation...');
        $this->runCommand('php tempest cyclone:sync-posts', '❌ Error syncing posts.');
        $this->console->info('✅ Posts synchonized!');
        $this->console->info('-----------------------------------------');
        
        // Step 11: Install Tailwind Typography dependencies
        $this->console->info('➡️ Starting installing Tailwind Typography...');
        $this->runCommand('npm install -D @tailwindcss/typography', '❌ Error installing @tailwindcss/typography.');
        $this->console->info('✅ Tailwind Typography installed!');
        $this->console->info('-----------------------------------------');
        
        // Step 11: Install Tailwind Typography dependencies
        $this->console->info('➡️ Starting installing Milkdown...');
        $this->runCommand('npm install @milkdown/crepe', '❌ Error installing @milkdown/crepe.');
        $this->console->info('✅ Milkdown installed!');
        $this->console->info('-----------------------------------------');
        
        // Step 12: Install npm dependencies
        // $this->console->info('Starting TempestPHP installation...');
        // $this->runCommand('npm install', '❌ Error installing npm dependencies.');
        // $this->console->info('✅ Milkdown installed!');
        // $this->console->info('-----------------------------------------');
        
        // Step 13: Install npm dependencies
        $this->console->info('➡️ Starting npm update...');
        $this->runCommand('npm update', '❌ Error installing npm dependencies.');
        $this->console->info('✅ npm updated!');
        $this->console->info('-----------------------------------------');
        
        // Step 13: Run dev mode
        // $this->runCommand('npm run dev -- --no-open', '❌ Error running npm run dev.');
        
        // todo cp .env.example to .env with correct url
        // Step 14: Copy .env.example to .env
        $this->console->info('➡️ Starting customizing .env...');
        $envexamplePath = root_path() . '/.env.example';
        $envPath = root_path() . '/.env';
        if (file_exists($envexamplePath)) {
            if (!copy($envexamplePath, $envPath)) {
                $this->console->error("❌ Error copying .env.example to .env");
                exit(1);
            }
            $this->console->info(".env.example copied to .env");
        } else {
            $this->console->error(".env.example file does not exist. Please create it manually.");
            exit(1);
        }  
        
        // Find BASE_URI=localhost and replace 'localhost' with the current URL
        $currentUrl = $this->console->ask('What will be the current URL of your site? (e.g., https://cyclone.test)', default: 'https://cyclone.test');
        $currentUrl = rtrim($currentUrl, '/'); // Remove trailing slash
        $envContent = file_get_contents($envPath);
        if ($envContent === false) {
            $this->console->error("❌ Error reading .env file");
            exit(1);
        }   
        $envContent = preg_replace('/^BASE_URI=.*$/m', 'BASE_URI=' . $currentUrl, $envContent);
        if (file_put_contents($envPath, $envContent) === false) {
            $this->console->error("❌ Error writing to .env file");
            exit(1);
        }
        $this->console->info("BASE_URI set to $currentUrl in .env file");
        $this->console->info('✅ .env updated!');
        $this->console->info('-----------------------------------------');
        
        
        $this->success('✅ Cyclone CMS installed successfully!');

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
