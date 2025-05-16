<?php

namespace App\Views\Post;

use App\models\Post;
use Tempest\View\View;
use Tempest\View\IsView;

use function Tempest\root_path;

/**
 * Undocumented class
 */
final class PostsListView implements View
{
    use IsView;

    public function __construct(
        public readonly array $posts,
        public readonly int $page,
        public readonly int $totalPages,
    ) {
        $this->path = root_path('vendor/happytodev/cyclone/src/Views/Post/list.view.php');
    }
}
