<?php

namespace App\Views;

use Tempest\View\View;
use Tempest\View\ViewProcessor;
use App\models\Post;

final class LastNewsViewProcessor implements ViewProcessor
{
    public function __construct(
        private array $lastnews,
    ) {
        $this->lastnews = Post::select()
            ->orderBy('created_at DESC')
            ->limit(4)
            ->with('user')
            ->all();
    }

    public function process(View $view): View
    {
        // Inject $lastnews variable in every view
        return $view->data(lastnews: $this->lastnews);
    }
}
