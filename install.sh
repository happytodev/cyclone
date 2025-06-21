#!/bin/bash

composer install
echo '✅ composer install executed.'
./vendor/bin/tempest install framework
echo '✅ Tempest install executed.'
touch .gitignore
echo '✅ .gitignore created.'
php tempest install vite --tailwind --npm
php tempest install auth
php tempest migrate:up
php tempest cyclone:add-user
php tempest cyclone:add-blog-post
php tempest cyclone:assets
php tempest cyclone:sync-posts
npm install -D @tailwindcss/typography
npm install && npm run dev
