# Why does it exist?

I read the [Laravel Beyond CRUD](https://stitcher.io/blog/laravel-beyond-crud-01-domain-oriented-laravel) series and decided *this is the way*.
But I'm a lazy coder: I really don't like to write a lot of code. So, I needed a toolkit to automate as much coding as possible. And here it is!

## Requirements

- PHP 7.2.5+
- Laravel 7+

# Laravel 11 support

I'm a little busy in this period. Laravel 11 has some critical changes that impact this package.
In the next few days I'll try to find the time to make a new major release with full support for Laravel 11.

## What it does?

It provides:

- multiple console commands to create domain driven classes
- traits and classes to speed up development

The commands prefix is `domain:`.

## How does it work?

Let's start with a clean Laravel installation. From your favorite shell:

```
composer create-project laravel/laravel project
```

Then install the library:

```
cd project
composer require filippo-toso/laravel-domain-library
composer require spatie/laravel-data spatie/laravel-model-states spatie/laravel-query-builder spatie/laravel-view-models
```

The additional Spatie's packages are required for data transfer objects, model states, query builders and view models.

Now the fun part... First, we are going to build the domain structure:

```
php artisan domain:setup:structure
```

This command will create a new `src` folder that will contain 3 namespaces `App`, `Domain` and `Support`.
It will also rewire the Laravel application to work with this new directory structure (ie. moving middlewares and providers, updating the bootstrap code, introducing a new Application class, and so on).

At this point you can start building your domain driven application. Let's create the structure for a sample domain named `Invoices`

```
php artisan domain:make:domain Invoices
```

Then we will prepare the administrative application part for this domain:

```
php artisan domain:make:application Admin\Invoices
```

## Making domain classes

With this library you can make the following classes:

- Models (with support for custom collections, query builders and subscribed events)
- Model's states (using spatie/laravel-model-states)
- Model's subscribers and model events (automagically linked to models)
- Model's query builders (automagically loaded)
- Model's collections (automagically loaded)
- Data transfer objects (using spatie/data-transfer-object)
- HTTP queries (using spatie/laravel-query-builder)
- View models (using spatie/laravel-view-models)
- Actions
- Form requests

All the commands include a `--force` option to overwrite existing classes. Please keep in mind that *with great power comes great responsibility*

#### Customizing code generation

If you want to customize the generated code, you can publish the stubs and then edit them.

```
php artisan vendor:publish --tag=stubs --provider="FilippoToso\Domain\Support\ServiceProvider"
```

#### Making models

```
php artisan domain:make:model Invoice --domain=Invoices
```

#### Making model's states

```
php artisan domain:make:states --model=Invoice --domain=Invoices --states="Paid,Pending,Overdue,Cancelled"
```

#### Making model's subscribers

```
php artisan domain:make:subscriber --model=Invoice --domain=Invoices
```

#### Making model's events

```
php artisan domain:make:events --model=Invoice --domain=Invoices --events="saving,created,deleting"
```

#### Making model's subscribers and events

```
php artisan domain:make:subscriber --model=Invoice --domain=Invoices --events="saving,created,deleting"
```

#### Making model's collections

```
php artisan domain:make:collection --model=Invoice --domain=Invoices
```

#### Making model's query builders

```
php artisan domain:make:querybuilder --model=Invoice --domain=Invoices
```

#### Making data transfer objects

```
php artisan domain:make:dto Invoice --domain=Invoices --application=Admin\Invoices
```

#### Making HTTP queries

```
php artisan domain:make:query InvoiceIndex --domain=Invoices --application=Admin\Invoices --model=Invoice
```

#### Making view models

```
php artisan domain:make:viewmodel InvoiceForm --domain=Invoices --application=Admin\Invoices --model=Invoice
```

#### Making actions

```
php artisan domain:make:action InvoiceIndex --domain=Invoices --application=Admin\Invoices --model=Invoice
```

#### Making form requests

```
php artisan domain:make:request Invoice --application=Admin\Invoices
```

#### Making exceptions

```
php artisan domain:make:exception InvalidInvoice --domain=Invoices
```

### Making a suite of classes

This command will create all the classes above in a single sweep:

```
php artisan domain:make:suite 
    --domain=Invoices 
    --application=Admin\Invoices 
    --model=Invoice 
    --states="Paid,Pending,Overdue,Cancelled"
    --events="saving,created,deleting" 
    --dtos="Invoice,CreateInvoice"
    --queries=InvoiceIndex 
    --dtos="Invoice,CreateInvoice"
    --exceptions=InvalidInvoice 
    --queries=InvoiceIndex 
    --requests=Invoice 
    --viewmodels=InvoiceForm 
    --actions="CreateInvoice,PayInvoice,CancelInvoice"
```
