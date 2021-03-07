# Why does it exist?

I read the [Laravel Beyound CRUD](https://stitcher.io/blog/laravel-beyond-crud-01-domain-oriented-laravel) series and decided *this is the way*.
But I'm a lazy coder: I really don't like to write a lot of code. So, I needed a toolkit to automate as much coding as possible. And here it is!

## Requirements

- PHP 7.2.5+
- Laravel 7+

## What it does?

It provides:

- multiple console commands to create DDD classes
- traits and classes to speed up development

The commands prefix is `domain:`.

## How does it work?

Let's start with a clean Laravel installation. From your favorite shell:

```
laravel new project
```

Then install the library:

```
cd project
composer require filippo-toso/laravel-domain-library
```

Now the fun part... First, we are going to build the domain structure:

```
php artisan domain:setup:structure
composer dump-autoload
```

This command will create a new `src` folder that will contain 3 namespaces `App`, `Domain` and `Support`.
It will also rewire the Laravel application to work with this new directory structure (ie. moving middlewares and providers, updating the bootstrap code, introducing a new Application class, and so on). Remember to dump the composer autoload in order to make the changes effective.

At this point you can start building your domain driven application. Let's create the structure for a sample domain named `Invoices`

```
php artisan domain:make:domain Invoices
```

Then we will prepare the administrative application part for this domain:

```
php artisan domain:make:application Admin\Invoices
```

That's it for the moment. In the next releases I'll add support for building other classes.

