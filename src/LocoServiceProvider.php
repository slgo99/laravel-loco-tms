<?php

namespace Jobilla\Loco;

use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Translation\Translator;
use Jobilla\Loco\Commands\DownloadTranslations;
use Jobilla\Loco\Commands\UploadTranslations;
use Loco\Http\ApiClient;

class LocoServiceProvider extends AggregateServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/loco.php', 'loco');

        $this->app->bind(ApiClient::class, function ($app) {
            return ApiClient::factory([
                'version' => '1.0',
                'key' => $app['config']->get('loco.api_key'),
                'validate_response' => false,
            ]);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/loco.php' => config_path('loco.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                UploadTranslations::class,
                DownloadTranslations::class,
            ]);
        }
    }
}
