
## Introduction

Cyclone is the first blog engine made with Tempest framework.

## Install

### Install a new tempest project : 

```bash
composer create-project tempest/app cyclonedemo --stability beta
```

```bash
php tempest install vite
```

### Chores

remove following files from your fresh install : 

- `app/x-base.view.php` 
- `app/home.view.php` 
- `app/HomeController.php` 


### Add cyclone as dependencies

```bash
composer require happytodev/cyclone
```

### Install built-in user model

```bash
php tempest install auth
php tempest migrate:up
```

This will install the User model and auth provided by Tempest framework

The Tempest's migrations will run first, the Cyclone's migrations in second.

### Set up the assets

```bash
php tempest cyclone:assets
```

For now, only logo.webp, main.entrypoint.css and mail.entrypoint.ts will be copied.

### run the front end development server

```bash
npm run dev
```