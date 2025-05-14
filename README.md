


## Install

### Install a new tempest project : 

@todo

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

