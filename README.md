
## Introduction

Cyclone is the first blog engine made with Tempest framework.

## How to install Cyclone?

It's very easy.

First, you need to create a folder for your project: 

```bash
mkdir cyclone-demo
cd cyclone-demo
```

Next, as Cyclone is in alpha, you have to set a minimal composer.json to start. So edit your composer.json with your favorite editor (for example `nano composer.json`) and add this content:

```json
{
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

Next, 

```bash
composer require happytodev/cyclone
```

When composer is done, lauch the installer:

```bash
./vendor/bin/tempest cyclone:install
```

At the end of install, if everything is ok, you can launch your server and go to your new blog:

https://cyclone-demo.test 


Last point, set the url of your project in the `.env` file.

```bash
nano .env 
```

And set the `BASE_URI` variable with your url.

```env
...
# The base URI that's used for all generated URIs
BASE_URI=https://cyclone-demo.test
...
```

And voilà!


## How to add content in Cyclone?

Just by adding some markdown files in the `content/blog` folder.


## Roadmap

A lot of work to do, but the main points are:
- [ ] Add a login page
- [ ] Add a dashboard
- [ ] Add a way to add content
- [ ] Add a way to edit content
- [ ] Add a way to delete content
- [ ] Add pages (CMS part)
- [ ] Add categories
- [ ] Add tags
- [ ] Add comments
- [ ] Add a search engine
- [ ] Add a way to add a custom theme
- [ ] Add a way to add a custom plugin
- etc...

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
 Vulnerabilities should be reported to happytodev@gmail.com.
 Please see [SECURITY](SECURITY.md) for more information.

## Credits

- [Frédéric Blanc](https://github.com/happytodev)
- [All Contributors](../../contributors)
