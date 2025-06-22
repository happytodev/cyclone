<?php

namespace HappyToDev\Cyclone\Commands;

use Tempest\Console\HasConsole;
use Tempest\Console\ConsoleCommand;

final class InstallCommand
{
    use HasConsole;

    #[ConsoleCommand('install')]
    public function __invoke(): void
    {
        $this->exec('./vendor/bin/tempest install framework');
        $this->exec('touch .gitignore');
        $this->exec('php tempest install vite --tailwind --npm');
        $this->exec('php tempest install auth');
        $this->exec('php tempest migrate:up');
        $this->exec('php tempest cyclone:add-user');
        $this->exec('php tempest cyclone:add-blog-post');
        $this->exec('php tempest cyclone:assets');
        $this->exec('php tempest cyclone:sync-posts');
        $this->exec('npm install -D @tailwindcss/typography');
        $this->exec('npm install && npm run dev');
        $this->success('Cyclone CMS installé avec succès !');
    }
}