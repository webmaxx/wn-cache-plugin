<?php namespace Webmaxx\Cache\Console;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Webmaxx\Cache\Enums\Path;
use Webmaxx\Cache\Enums\RegEx;
use Winter\Storm\Console\Command;

class ClearUploadImageThumbs extends Command
{
    /**
     * @var string The console command name.
     */
    protected static $defaultName = 'clear-upload-image-thumbs';

    /**
     * @var string The name and signature of this command.
     */
    protected $signature = 'clear-upload-image-thumbs
        {--path= : Path to upload images. Default: <info>"/app/uploads/public"</info>}
        {--thumb-regex= : RegEx for thumb file names. Default: <info>"/^thumb_.*/"</info>}';

    /**
     * @var string The console command description.
     */
    protected $description = 'Delete upload image thumbs';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        $path = storage_path() . ($this->option('path') ?: Path::UPLOAD_IMAGES->value);
        $regex = $this->option('thumb-regex') ?: RegEx::UPLOAD_IMAGE_THUMBS->value;
        $directoryIterator = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($iterator as $file) {
            if (preg_match($regex, $file->getFilename())) {
                @unlink($file->getRealPath());
            }
        }
    }
}
