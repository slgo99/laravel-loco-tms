# Laravel Loco TMS

This is an integration with the [Loco Translation Management System](https://localise.biz).

## Installation

```sh
composer require jobilla/laravel-loco-tms
```

## Configuration

To use the library, you'll need to set your Loco API Key for your translation project.
Either set the environment variable `LOCO_API_KEY`, or publish the configuration file
and update it with another value.

To publish the configuration file, run:
```php
php artisan vendor:publish --provider="Jobilla\Loco\LocoServiceProvider"
```
