# evista/composer

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


This is a Wordpress plugin that helps to use composer with a lazy loader service container (dependency injector).

## Install


## Usage

``` php
echo $container->get('twig.templating')->render('index.twig.html', ['hehh' => 'Yeah!']);
```

## Documentation

Evista Composer plugin makes the marvellous Composer accessible in any WordPress project. If you never heard about it, Composer is a PHP package manager with a very good dependency resolver that makes using of third party libraries a breeze.

Composer transformed the PHP ecosystem recently so you should probably learn it.<div>If you want to use the latest Twig templating engine in your WordPress plugins or themes you only have to
1. run composer require twig/twig in the module’s root dir
2. add the new package to the lazy loader service container’s setup

Afterwards the Twig rendering engine will be accessible from anywhere this way:


```php
echo $container->get('twig.templating')->render('index.twig.html', ['hehh' => 'Yeah!']);
```

### How to add a package to the container

Every package has a different way of initializing, the container helps collect them under a unified API -- and besides it helps decoupling your code (decoupling makes your code more maintanable and solid)

Twig’s documentation says this is the way you can instantiates Twig after installing it:

```php
$loader = new Twig_Loader_Filesystem('/path/to/templates');
$twig = new Twig_Environment($loader, array(
    'cache' => '/path/to/compilation_cache',
));

echo $twig->render('index.html', array('name' => 'Fabien'));
```


So the container needs to instantiate two classes, the Twig_Loader_Filesystem and using it as an argument the Twig_Environment class, that can be used to rendering directly. The container can solve this dependency easily if we define two services, and use the first as an argument of the second:


```php
//wp-content/plugins/evista-composer/Composer.php
  
  
/**
     * Add service definitions here:
     *
     */
  public function setUpServices(){
      $this->services = [
          'twig.loader' =>[
              'class' => '\Twig_Loader_Filesystem',
              'arguments' => [__DIR__.'/views']
          ],
          'twig.templating' => [
              'class' => '\Twig_Environment',
              'arguments' => ['@twig.loader']
          ],
      ];
    }
```


The Composer::services variable holds all the needed informations. Every one of them requires a unique name, that will identify them throughout WordPress. These names will be the keys of the services array, and the most important properties of the services in an array are the values.

In the above example, ‘twig.templating’ is the name, this is how it can be requested from the container:


```php
$templating = $container->get('twig.templating');
$page = $templating->render('index.twig.html', ['hehh' => 'Yeah!']);
```


In the configuration array above there are two important key value pairs: class and arguments.

The first tells the container which class should it use to instantiate the service (with namespaces), and second tells what argument to use in what order. So far strings, flat (one level) arrays and other services are supported as argument.


```php
public function setUpServices(){
      $this->services = [
          'sample.service' =>[
              'class' => '\SampleService',
              'arguments' => ['string', '@other.service', ['first'=>'1', "second" => '2']]
          ],
          // ..
      ];
    }
```

And this is it. After modifying Composer you are ready to use your new package installed via composer.



## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email sera.balint@e-vista.hu instead of using the issue tracker.

## Credits

- [Balint Sera][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/league/evista/clean_code.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/thephpleague/evista/clean_code/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/thephpleague/evista/clean_code.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/thephpleague/evista/clean_code.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/league/evista/clean_code.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/league/evista/clean_code
[link-travis]: https://travis-ci.org/thephpleague/evista/clean_code
[link-scrutinizer]: https://scrutinizer-ci.com/g/thephpleague/evista/clean_code/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/thephpleague/evista/clean_code
[link-downloads]: https://packagist.org/packages/league/evista/clean_code
[link-author]: https://github.com/serabalint
[link-contributors]: ../../contributors