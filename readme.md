# Laravel Loco TMS

This is an integration with the [Loco Translation Management System](https://localise.biz).

## Installation

```sh
composer require jobilla/laravel-loco-tms
```

## Configuration

To use the library, you'll need to set your Loco API Key for your translation project.
Set the environment variable `LOCO_API_KEY`.

Then you'll need to publish the configuration file.

To publish the configuration file, run:
```sh
php artisan vendor:publish --provider="Jobilla\Loco\LocoServiceProvider"
```
This will create a `loco.php` file in your `config` directory.

The configuration file should look like this:
```php
return [
    'api_key' => env('LOCO_API_KEY'),
    'lang_path' => resource_path('lang'),
];
```
The `lang_path` is the path where the translations will be stored.
In Laravel 9+ projects, you might need to set the `lang_path` to `base_path('lang')`.

## Usage

### Importing translations

To download translations from Loco, run the following command:

```sh
php artisan loco:download
```
This will download all translations from Loco and store them in the `lang_path` directory.

### Exporting translations

To upload translations to Loco, run the following command:

```sh
php artisan loco:upload
```
This will upload all translations from the `lang_path` directory to Loco.
