<?php

namespace App\models;

use App\Auth\User;
use Tempest\Database\IsDatabaseModel;
use Tempest\Router\Bindable;

final class Post implements Bindable
{
    use IsDatabaseModel;

    public ?User $user = null;

    public string $title;

    public string $slug;

    public string $tldr;

    public string $markdown_file_path;

    public ?\DateTimeImmutable $created_at;

    public ?\DateTimeImmutable $published_at;

    public int $user_id;

    public string $cover_image;

    public function getContent(): string
    {
        return file_get_contents($this->markdown_file_path);
    }

    public static function resolve(string $input): static
    {
        return static::select()
            ->where('slug == ?', $input)
            ->with('user')
            ->first();
    }
}
