<?php

declare(strict_types=1);

namespace Happytodev\Cyclone\Controllers;

use App\Auth\User;
use DateTimeImmutable;
use Tempest\View\View;

use Tempest\Router\Get;

use function Tempest\root_path;
use function Tempest\view;
use Happytodev\Cyclone\Models\Post;
use Happytodev\Cyclone\Repositories\PostRepository;

final readonly class HomeController
{
    public function __construct(
        private PostRepository $repository,
    ) {}

    #[Get('/')]
    public function __invoke(): View
    {
        $posts = Post::select()
            ->orderBy('created_at DESC')
            ->limit(3)
            ->with('user')
            ->all();
        return view(__DIR__ . '/../Views/home.view.php', posts: $posts);
    }
}
