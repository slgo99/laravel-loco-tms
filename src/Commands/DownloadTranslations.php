<?php

namespace Jobilla\Loco\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Translation\Translator;
use Loco\Http\ApiClient;

class DownloadTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loco:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download current translations in all languages from Loco';

    /**
     * Execute the console command.
     *
     * @param ApiClient $client
     * @param Translator $translator
     * @param FilesystemManager $storage
     */
    public function handle(ApiClient $client, Translator $translator, FilesystemManager $storage)
    {
        $res = $client->exportAll([
            'ext' => 'json',
        ]);

        $translations = $this->dropEmptyString(json_decode((string)$res, JSON_OBJECT_AS_ARRAY));
        $fs = $storage->createLocalDriver(['root' => config('loco.lang_path')]);

        foreach ($translations as $lang => $groups) {
            $this->info("Writing translation files for $lang...");
            foreach ($groups as $group => $keys) {
                $output = var_export($keys, true);
                $fileName = str_replace('-', '_', $lang);
                $fs->put("$fileName/$group.php", "<?php\n\nreturn $output;\n");
                $this->info("✔ Successfully saved $group.php");
            }
            $this->info("✔ Downloaded and saved all translations for $lang");
        }

        $this->info("✔ All translations downloaded");
    }

    protected function dropEmptyString(array $array) {
        $array = array_map(function ($value) {
            if (is_array($value)) {
                return $this->dropEmptyString($value);
            }

            return $value;
        }, $array);

        return array_filter($array, function($value) {
            return !is_null($value) && $value !== '';
        });
    }
}
