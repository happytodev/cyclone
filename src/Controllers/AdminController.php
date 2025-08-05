<?php

namespace Happytodev\Cyclone\Controllers;

use Exception;
use App\Auth\User;
use DateTimeImmutable;
use Tempest\View\View;
use Tempest\Auth\Allow;
use Tempest\Log\Logger;
use Tempest\Router\Get;
use Tempest\Http\Status;
use Tempest\Router\Post;
use function Tempest\map;
use function Tempest\uri;
use Tempest\Http\Request;
use function Tempest\view;
use Tempest\Http\JsonResponse;
use function Tempest\root_path;

use Tempest\Auth\Authenticator;
use Symfony\Component\Yaml\Yaml;
use Tempest\Http\GenericResponse;
use Tempest\Http\Session\Session;
use Tempest\Http\Responses\Redirect;
use Tempest\Auth\SessionAuthenticator;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Happytodev\Cyclone\Requests\PostRequest;
use League\CommonMark\Environment\Environment;
use Happytodev\Cyclone\Middlewares\IsUserAdmin;
use Happytodev\Cyclone\Middlewares\IsUserConnected;
use Happytodev\Cyclone\Repositories\PostRepository;
use Tempest\Highlight\CommonMark\HighlightExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;

class AdminController
{

    private $user = null;

    public function __construct(
        private Authenticator $authenticator,
        private PostRepository $postRepository,
        private Logger $logger,
    ) {
        // Get the current user from the authenticator
        $this->user = $this->authenticator->currentUser();
        // Log the current user
        $this->logger->info("Current user in AdminController", ['user' => $this->user, 'id' => $this->user?->id]);
    }


    #[Get('/admin/login')]
    public function showLogin(): View
    {
        return view('../Views/login.view.php');
    }

    #[Allow('admin')]
    #[Get('/admin', middleware: [IsUserConnected::class, IsUserAdmin::class])]
    public function index(SessionAuthenticator $session): View|Redirect
    {
        $posts = $this->postRepository->getAllPosts();

        return view('../Views/Admin/index.view.php', posts: $posts);
    }

    #[Allow('admin')]
    #[Get('/admin/edit/{slug}', middleware: [IsUserConnected::class, IsUserAdmin::class])]
    public function edit(string $slug): View
    {
        $post = $this->postRepository->findBySlug($slug);

        $markdownPath = root_path() . DIRECTORY_SEPARATOR . $post->markdown_file_path;
        $environment = new Environment();

        $environment
            ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new HighlightExtension());

        if (file_exists($markdownPath)) {
            $markdownContent = file_get_contents($markdownPath);
            $document = YamlFrontMatter::parse($markdownContent);
            $markdownBody = $document->body(); // Raw Markdown
        } else {
            $markdownBody = 'Content not found'; 
        }

        // ld($markdownPath, $markdownBody);
        // Passer le Markdown brut à la vue
        return view('../Views/Admin/edit.view.php', post: $post, markdown: $markdownBody);
    }

    #[Post('/admin/save', middleware: [IsUserConnected::class, IsUserAdmin::class])]
    public function store(PostRequest $request): Redirect
    {

        $slug = $request->get('slug');
        $title = $request->get('title');
        $tldr = $request->get('tldr');
        $markdown_file_path = $request->get('markdown_file_path');
        $cover_image = $request->get('cover_image');
        $published = $request->get('published');
        $markdownContent = ltrim($request->get('markdown'));

       
        // Find the post in the database via the slug        
        $post = $this->postRepository->findBySlug($slug);

        if (!$post) {
            // If the post is not found, redirect with an error or to a default page
            return new Redirect('/admin');
        }

        // Update the post metadata
        $post->title = $title;
        $post->save(); // Save modifications in database


        // Build the frontmatter 
        $frontmatter = [
            'title' => $title,
            'slug' => $slug,
            'tldr' => $tldr,
            'markdown_file_path' => $markdown_file_path,
            'cover_image' => $cover_image,
            'published' => $published,
        ];


        // Convert frontmatter in YAML
        $frontmatterYaml = Yaml::dump($frontmatter);

        // Combine frontmatter and Markdown content
        $fullContent = "---\n" . $frontmatterYaml . "---\n" . $markdownContent;

        //  Markdown file path
        $markdownPath = root_path() . DIRECTORY_SEPARATOR . $post->markdown_file_path;

        // Write content in markdown file
        $writeResult = file_put_contents($markdownPath, $fullContent);

        return new Redirect(uri([BlogController::class, 'show'], slug: $post->slug));
    }


    #[Post('/admin/upload-image')]
    public function uploadImage(Request $request): GenericResponse
    {
        // Check if a file was sended and check this is an image
        $files = $request->files;
        $file = $files['file'] ?? null;

        if (!$file || $file->getError() || !str_starts_with($file->getClientMediaType(), 'image/')) {
            ll('Invalid file or not an image');
            $data = json_encode(['error' => 'Invalid file or not an image']);
            return new GenericResponse(status: Status::BAD_REQUEST, body: $data, headers: ['Content-Type' => 'application/json']);
        }

        // Define storage folder
        $uploadDir = root_path() . '/public/uploads';
        ll('Upload directory: ' . $uploadDir);
        if (!is_dir($uploadDir)) {
            ll('Directory does not exist, creating...');
            mkdir($uploadDir, 0755, true); // Create directory if does not exist
        }

        // Generate uniq filename
        $filename = uniqid() . '.' . explode('/', $file->getClientMediaType())[1];
        $filePath = $uploadDir . '/' . $filename;
        ll('File path: ' . $filePath);

        try {
            // Move the uploaded file to the target directory
            $file->moveTo($filePath);

            // Check if the file exists at the target location
            if (file_exists($filePath)) {
                $url = '/uploads/' . $filename;
                ll('File uploaded successfully, URL: ' . $url);
                $data = json_encode(['url' => $url]);
                return new GenericResponse(status: Status::CREATED, body: $data, headers: ['Content-Type' => 'application/json']);
            } else {
                ll('File not found after moveTo');
                $data = json_encode(['error' => 'Upload failed']);
                return new GenericResponse(status: Status::INTERNAL_SERVER_ERROR, body: $data, headers: ['Content-Type' => 'application/json']);
            }
        } catch (\Exception $e) {
            ll('Exception during file upload: ' . $e->getMessage());
            $data = json_encode(['error' => 'Exception during file upload : ' . $e->getMessage()]);
            return new GenericResponse(status: Status::INTERNAL_SERVER_ERROR, body: $data, headers: ['Content-Type' => 'application/json']);
        }
    }

    #[Get('/admin/posts/create', middleware: [IsUserConnected::class, IsUserAdmin::class])]
    public function create(): View
    {
        return view('../Views/Admin/create.view.php');
    }


    function generateSlug(string $title): string
    {
        // Normalize accented characters to ASCII
        $title = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title);

        // remove all non-alphanumeric characters except spaces
        $title = preg_replace('/[^A-Za-z0-9\s]/', '', $title);

        // Convert into lowercase
        $title = strtolower($title);

        // Replace spaces with hyphens
        $slug = preg_replace('/\s+/', '-', $title);

        // Remove multiple hyphens and trim hyphens from the start and end
        $slug = trim(preg_replace('/-+/', '-', $slug), '-');

        return $slug;
    }

    #[Post('/admin/posts', middleware: [IsUserConnected::class, IsUserAdmin::class])]
    public function storeNew(PostRequest $request): Redirect
    {
        ll("create post request", $request);
        // Get the form data
        $title = $request->get('title');
        $tldr = $request->get('tldr') ?? '';
        $markdownContent = ltrim($request->get('markdown'));
        $cover_image = $request->get('cover_image') ?? null;
        $published = $request->get('published') ?? false;

        // Generate unique slug from title
        $slug = $this->generateSlug($title);
        // $slug = strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $title));
        $existingPost = $this->postRepository->findBySlug($slug);
        if ($existingPost) {
            $slug = $slug . '-' . time(); // add a timestamp to make it unique
        }

        // Create a new Post object
        $post = new \Happytodev\Cyclone\Models\Post(); 
        $post->title = $title;
        $post->slug = $slug;
        $post->tldr = $tldr;
        $post->markdown_file_path = 'content/blog/' . $slug . '.md';
        $post->cover_image = $cover_image;
        $post->published = $published;
        $post->created_at = new \DateTimeImmutable();
        $post->published_at = $published ? new \DateTimeImmutable() : null;
        $post->user_id = $this->user?->id->id;
        $post->save();

        // Build frontmatter
        $frontmatter = [
            'title' => $title,
            'slug' => $slug,
            'tldr' => $tldr,
            'markdown_file_path' => $post->markdown_file_path,
            'cover_image' => $cover_image,
            'published' => $published,
        ];
        $frontmatterYaml = Yaml::dump($frontmatter);

        // Combine frontmatter and Markdown content
        $fullContent = "---\n" . $frontmatterYaml . "---\n" . $markdownContent;

        // Check and create the directory if necessary
        $dir = root_path() . '/content/blog';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Write the Markdown file
        $markdownPath = $dir . '/' . $slug . '.md';
        file_put_contents($markdownPath, $fullContent);

        // Redirect to the new post page
        return new Redirect(uri([BlogController::class, 'show'], slug: $post->slug));
    }

    #[Post('/admin/posts/{slug}/delete', middleware: [IsUserConnected::class, IsUserAdmin::class])]
    public function destroy(string $slug): Redirect
    {
        $post = $this->postRepository->findBySlug($slug);
        if ($post) {
            // remove the associated markdown file
            $markdownPath = root_path() . '/' . $post->markdown_file_path;
            if (file_exists($markdownPath)) {
                unlink($markdownPath);
            }
            // remove the post from the database
            $post->delete();
        }
        // redirect to the posts index
        return new Redirect(uri([self::class, 'index']));
    }
}
