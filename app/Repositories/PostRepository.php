<?php

namespace App\Repositories;

use App\models\Post;
use DateTimeImmutable;
use Tempest\Database\Id;

use function Tempest\Database\query;

final class PostRepository
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


    /**
     * Delete posts without maching file
     *
     * @param array $existingSlugs
     * @return boolean
     */
    public function deletePostsWithoutMachingFile(array $existingSlugs): bool
    {
        // Check that the table is not empty to avoid an SQL error
        if (empty($existingSlugs)) {
            // Special case: delete all posts if $existingSlugs is empty
            $queryBuilder = query(Post::class)
                ->delete()
                ->allowAll(); // Deletes all lines
        } else {
            // Generate placeholders for NOT IN
            $placeholders = implode(', ', array_fill(0, count($existingSlugs), '?'));
            $condition = "slug NOT IN ($placeholders)";

            // Build the DELETE request
            $queryBuilder = query(Post::class)
                ->delete()
                ->where($condition, ...$existingSlugs);
        }

        // Generate the SQL query
        $query = $queryBuilder->build();

        // Execute the request
        $result = $query->execute();

        if ($result instanceof Id && $result->id === 0) {
            return true;
        }

        return false;
    }
}
