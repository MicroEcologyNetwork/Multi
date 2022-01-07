# Installation

> This package requires PHP 7+ and Laravel 5.5, for old versions please refer to [1.4](http://laravel-multi.org/docs/v1.4/#/)

First, install laravel, and make sure that the database connection settings are correct.

Then install require this package with command:
```
composer require encore/laravel-multi "1.5.*"
```

Publish assets and config with command：
```
php artisan vendor:publish --provider="MicroEcology\Multi\MultiServiceProvider"
```

After runnung previous command you can find config file in `config/multi.php`, in this file you can change default install directory (```/app/Multi```), db connection or table names.

At last run following command to finish install:
```
php artisan multi:install
```

To check that all is working, run `php artisan serve` and open `http://localhost/multi/` in browser, use username `multi` and password `multi` to login.

## Generated files

After the installation is complete, the following files are generated in the project directory:

### Configuration file

After the installation is complete, all configurations are in the `config/multi.php` file.

### Multi files

After install,you can find directory`app/Multi`,and then most of our develop work is under this directory.

```
app/Multi
├── Controllers
│   ├── ExampleController.php
│   └── HomeController.php
├── bootstrap.php
└── routes.php
```

`app/Multi/routes.php` is used to define routes.

`app/Multi/bootstrap.php` is bootstrapper for laravel-multi, for usage examples see comments inside it.

The `app/Multi/Controllers` directory is used to store all the controllers.
The `HomeController.php` file under this directory is used to handle home request of multi.
The `ExampleController.php` file is a controller example.

### Static assets

The front-end static files are in the `/public/packages/multi` directory.
