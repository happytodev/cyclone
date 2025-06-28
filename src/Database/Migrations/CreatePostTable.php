<?php

namespace Happytodev\Cyclone\Database\Migrations;

use Tempest\Database\QueryStatement;
use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatements\DropTableStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;

final class CreatePostTable implements DatabaseMigration
{
    public string $name = '2025-05-08_001_create_post_table';

    public function up(): QueryStatement|null
    {
        return new CreateTableStatement('posts')
            ->primary()
            // limit title to 240 caracters to keep 15 caracters to slug
            ->varchar('title', 240)
            ->varchar('slug')
            ->unique('slug')
            ->varchar('tldr')
            ->varchar('markdown_file_path')
            ->varchar('cover_image', nullable: true)
            ->datetime('created_at')
            ->datetime('published_at', nullable: true)
            ->boolean('published', nullable: false, default: false)
            ->integer('user_id', unsigned: true);
    }

    public function down(): QueryStatement|null
    {
        return new DropTableStatement('posts');
    }
}
