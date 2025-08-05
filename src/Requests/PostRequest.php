<?php

namespace Happytodev\Cyclone\Requests;

use DateTimeImmutable;
use Tempest\Http\Request;
use Tempest\Http\IsRequest;
use Tempest\Validation\Rules\Length;

final class PostRequest implements Request
{
    use IsRequest;
    
    #[Length(min: 3, max: 255)]
    public string $title;

    public string $slug;

    public string $tldr;

    public string $markdown_file_path;

    // public ?\DateTimeImmutable $created_at;

    // public ?\DateTimeImmutable $published_at;

    public string $cover_image;

    public bool $published;
}
