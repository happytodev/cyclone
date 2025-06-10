<?php

namespace App\Controllers;

use Tempest\View\View;
use Tempest\Router\Get;
use Tempest\Http\Request;

use function Tempest\view;

use App\Views\Post\PostsListView;
use App\Repositories\PostRepository;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use Tempest\Highlight\CommonMark\HighlightExtension;

final class BlogController
{

    public function __construct(
        private PostRepository $repository,
    ) {}

    #[Get('/blog')]
    public function __invoke(Request $request): View
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

        $rootPath = realpath(getcwd() . '/../');

        $markdownPath = realpath($rootPath . '/content/' . $post->markdown_file_path);
        dd($markdownPath);
        $environment = new Environment();

        $environment
            ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new HighlightExtension());

        if (file_exists($markdownPath)) {
            $markdownContent = file_get_contents($markdownPath);

            $converter = new MarkdownConverter($environment);
            $content = $converter->convert($markdownContent);

        } else {
            $content = 'Content not found';
        }

        return view('../Views/Post/show.view.php', post: $post, content: $content); 
    }
}
