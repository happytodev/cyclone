
## Introduction

Cyclone is the first blog engine made with Tempest framework.

## Install

Currently Cyclone is in alpha phase.

Create a new forlder for your project : 

```bash
mkdir mynewblog
cd mynewblog
```

Launch `composer init` and when it asks you for minimum stability, enter `dev`.

Edit your composer.json and add the following line 

`    "prefer-stable": true,`

under

`    "minimum-stability": "dev",`

Next, add Cyclone as dependency : 

```bash
composer require happytodev/cyclone
```

When install is finished, launch the following command : 

```bash
./vendor/bin/tempest cyclone:install
```

## Add content

For blog posts, put your content in `content/blog` folder.
Image for blog posts must take place in `public/img/blog`

