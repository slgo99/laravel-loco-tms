<?php

namespace Jobilla\Loco\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Translation\Translator;
use Loco\Http\ApiClient;

class UploadTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loco:upload {--locale=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export current English translations to the Loco TMS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param ApiClient $client
     * @param Translator $translator
     * @param FilesystemManager $storage
     * @return mixed
     */
    public function handle(ApiClient $client, Translator $translator, FilesystemManager $storage)
    {
        $fs = $storage->createLocalDriver(['root' => resource_path('lang')]);
        $locales = count($this->option('locale')) ? $this->option('locale') : $fs->directories();
        foreach ($locales as $locale) {
            $translations = [];
            foreach ($fs->allFiles($locale) as $file) {
                $category = basename($file, '.php');
                $translations[$category] = $translator->getLoader()->load($locale, $category);
            }

            $this->info("Sending $locale translations to Loco...");
            $res = $client->import([
                'locale' => $locale,
                'ext' => 'json',
                'data' => json_encode($translations)
            ]);
            $this->info("✔ $locale upload complete: {$res['message']}");
        }

        $this->info('✔ All uploads completed');
    }
}
