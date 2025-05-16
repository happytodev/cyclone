<?php

namespace App\Repositories;

use DateTimeImmutable;
use App\models\Post;
use function Tempest\Database\query;

class PostRepository
{

    /**
     * Return all posts
     *
     * @return array of posts
     */
    public function getAllPosts(): array
    {
        return Post::select()
            ->orderBy('created_at DESC')
            ->with('user')
            ->all();
    }

    /**
     * Retrieves posts with pagination support.
     *
     * @param int $limit Number of posts to retrieve
     * @param int $offset Offset for pagination
     * @return array List of posts
     */
    public function getPosts(int $limit, int $offset = 0): array
    {
        return Post::select()
            ->orderBy('created_at DESC')
            ->with('user')
            ->limit($limit)
            ->offset($offset) // Add offset for pagination
            ->all();
    }

    /**
     * Get the total number of posts
     *
     * @return integer
     */
    public function getTotalPosts(): int
    {
        return query('posts')
            ->count()
            ->execute();
    }

    /**
     * Find a blog post by its slug
     *
     * @param string $slug
     * @return Post
     */
    public function findBySlug(string $slug): Post
    {
        return (Post::select()
            ->where('slug == ?', $slug)
            ->with('user')
            ->first());
    }
}
