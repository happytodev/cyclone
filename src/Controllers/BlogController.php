<?php

namespace Happytodev\Cyclone\Controllers;

use Tempest\View\View;
use Tempest\Router\Get;
use Tempest\Http\Request;

use function Tempest\view;
use function Tempest\root_path;

use Happytodev\Cyclone\Views\Post\PostsListView;
use Happytodev\Cyclone\Repositories\PostRepository;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use League\CommonMark\Environment\Environment;
use Tempest\Highlight\CommonMark\HighlightExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;

final class BlogController
{

    public function __construct(
        private PostRepository $repository,
    ) {}

    #[Get('/blog')]
    public function index(Request $request): View
    {
        // Number of posts per page
        $perPage = 9;

        // Current page, minimum 1
        $page = max(1, (int)$request->get('page', 1));

        // Calculate offset for pagination
        $offset = ($page - 1) * $perPage;

        // Get paginated posts
        $posts = $this->repository->getPosts($perPage, $offset);

        // Get total number of posts
        $totalPosts = $this->repository->getTotalPosts();

        // Calculate total number of pages
        $totalPages = (int)ceil($totalPosts / $perPage);

        // Pass data to the view
        return new PostsListView($posts, $page, $totalPages);
    }


    #[Get(uri: '/blog/{slug}')]
    public function show(string $slug): View
    {
        $post = $this->repository->findBySlug($slug);

        $markdownPath = root_path() . DIRECTORY_SEPARATOR . $post->markdown_file_path;

        $environment = new Environment();

        $environment
            ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new HighlightExtension());

        if (file_exists($markdownPath)) {
            $markdownContent = file_get_contents($markdownPath);
            $document = YamlFrontMatter::parse($markdownContent);

            $converter = new MarkdownConverter($environment);
            $content = $converter->convert($document->body());
        } else {
            $content = 'Content not found';
        }

        return view('../Views/Post/show.view.php', post: $post, content: $content);
    }
}
