<?php

namespace Happytodev\Cyclone\Models;

use App\Auth\User;
use Tempest\Database\IsDatabaseModel;

final class Post
{
    use IsDatabaseModel;

    public string $title;

    public string $slug;

    public string $tldr;

    public string $markdown_file_path;

    public ?string $cover_image;

    public \DateTimeImmutable $created_at;

    public ?\DateTimeImmutable $published_at;

    public bool $published;

    public ?User $user = null;
}
