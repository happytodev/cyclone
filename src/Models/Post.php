<?php

namespace Happytodev\Cyclone\models;

use App\Auth\User;
use Tempest\Database\IsDatabaseModel;

final class Post
{
    use IsDatabaseModel;

    public ?User $user = null;

    public string $title;

    public string $slug;

    public string $tldr;

    public string $markdown_file_path;

    public ?\DateTimeImmutable $created_at; 
    
    public ?\DateTimeImmutable $published_at; 

    public function getContent(): string
    {
        return file_get_contents($this->markdown_file_path);
    }
}
