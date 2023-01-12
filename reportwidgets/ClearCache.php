<?php namespace Webmaxx\Cache\ReportWidgets;

use Lang;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Backend\Classes\ReportWidgetBase;
use Illuminate\Support\Facades\Artisan;
use Webmaxx\Cache\Enums\Path;
use Webmaxx\Cache\Enums\RegEx;

/**
 * ClearCache Report Widget
 */
class ClearCache extends ReportWidgetBase
{
    protected $del_upload_image_thumbs = false;
    protected $upload_images_path;
    protected $upload_image_thumbs_regex;

    /**
     * @var string The default alias to use for this widget
     */
    protected $defaultAlias = 'wmClearCacheReportWidget';

    /**
     * Defines the widget's properties
     * @return array
     */
    public function defineProperties(): array
    {
        return [
            'title' => [
                'title'             => 'backend::lang.dashboard.widget_title_label',
                'default'           => Lang::get('webmaxx.cache::lang.reportWidgets.clearCache.title.default'),
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ],
            'del_upload_image_thumbs' => [
                'title'   => 'webmaxx.cache::lang.reportWidgets.clearCache.del_upload_image_thumbs.title',
                'type'    => 'checkbox',
                'default' => $this->del_upload_image_thumbs,
            ],
            'upload_images_path' => [
                'title'       => 'webmaxx.cache::lang.reportWidgets.clearCache.upload_images_path.title',
                'type'        => 'string',
                'placeholder' => Path::UPLOAD_IMAGES->value,
            ],
            'upload_image_thumbs_regex' => [
                'title'       => 'webmaxx.cache::lang.reportWidgets.clearCache.upload_image_thumbs_regex.title',
                'type'        => 'string',
                'placeholder' => RegEx::UPLOAD_IMAGE_THUMBS->value,
            ]
        ];
    }

    /**
     * Renders the widget's primary contents.
     * @return string HTML markup supplied by this widget.
     */
    public function render(): string
    {
        try {
            $this->prepareVars();
        } catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('clearcache');
    }

    /**
     * Prepares the report widget view data
     */
    public function prepareVars(): void
    {
        $this->del_upload_image_thumbs = $this->property('del_upload_image_thumbs') ?: $this->del_upload_image_thumbs;
        $this->upload_images_path = $this->property('upload_images_path') ?: Path::UPLOAD_IMAGES->value;
        $this->upload_image_thumbs_regex = $this->property('upload_image_thumbs_regex') ?: RegEx::UPLOAD_IMAGE_THUMBS->value;

        $this->vars['del_upload_image_thumbs'] = $this->del_upload_image_thumbs;
        $this->vars['size'] = $this->loadSizes();
    }

    public function onClear(): array
    {
        $this->prepareVars();
        $this->clearCache();

        return [
            'partial' => $this->render(),
        ];
    }

    protected function loadSizes(): array
    {
        $sizes = [];

        $sizes['cms']['cache']['bites'] = $this->dirSize(storage_path() . Path::CMS_CACHE->value);
        $sizes['cms']['cache']['formatted'] = $this->formattedSize($sizes['cms']['cache']['bites']);

        $sizes['cms']['combiner']['bites'] = $this->dirSize(storage_path() . Path::CMS_COMBINER->value);
        $sizes['cms']['combiner']['formatted'] = $this->formattedSize($sizes['cms']['combiner']['bites']);

        $sizes['cms']['twig']['bites'] = $this->dirSize(storage_path() . Path::CMS_TWIG->value);
        $sizes['cms']['twig']['formatted'] = $this->formattedSize($sizes['cms']['twig']['bites']);

        $sizes['framework']['cache']['bites'] = $this->dirSize(storage_path() . Path::FRAMEWORK_CACHE->value);
        $sizes['framework']['cache']['formatted'] = $this->formattedSize($sizes['framework']['cache']['bites']);

        if ($this->del_upload_image_thumbs) {
            $sizes['images']['uploads']['bites'] = $this->dirSize(storage_path() . Path::UPLOAD_IMAGES->value);
            $sizes['images']['uploads']['formatted'] = $this->formattedSize($sizes['images']['uploads']['bites']);
        }

        $sizes['total']['bites'] = $sizes['cms']['cache']['bites']
                                   + $sizes['cms']['combiner']['bites']
                                   + $sizes['cms']['twig']['bites']
                                   + $sizes['framework']['cache']['bites']
                                   + ($this->del_upload_image_thumbs ? $sizes['images']['uploads']['bites'] : 0)
        ;
        $sizes['total']['formatted'] = $this->formattedSize($sizes['total']['bites']);

        return $sizes;
    }

    protected function dirSize($dir): int
    {
        if (!file_exists($dir) || count(scandir($dir)) <= 2) {
            return 0;
        }

        // $size = (int) trim(`du -s -B 1 {$dir} | cut -f1`);

        // if ($size !== false && $size !== null) {
        //     return $size;
        // }

        $size = 0;
        $directoryIterator = new RecursiveDirectoryIterator($dir);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($iterator as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    protected function formattedSize($size): string
    {
        $mod = 1024;
        $units = explode(',', 'B,KB,MB,GB,TB,PB');

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    protected function clearCache(): void
    {
        Artisan::call('cache:clear');

        if ($this->del_upload_image_thumbs) {
            Artisan::call('clear-upload-image-thumbs', [
                '--path' => $this->upload_images_path,
                '--thumb-regex' => $this->upload_image_thumbs_regex,
            ]);
        }
    }
}
