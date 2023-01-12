<?php namespace Webmaxx\Cache;

use System\Classes\PluginBase;

/**
 * Cache Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'webmaxx.cache::lang.plugin.name',
            'description' => 'webmaxx.cache::lang.plugin.description',
            'author'      => 'Webmaxx',
            'homepage'    => 'https://github.com/webmaxx/wn-cache-plugin',
            'icon'        => 'icon-file-archive-o'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {
        $this->registerConsoleCommands();
    }

    /**
     * Register report widgets.
     */
    public function registerReportWidgets(): array
    {
        return [
            'Webmaxx\Cache\ReportWidgets\ClearCache' => [
                'label'   => 'webmaxx.cache::lang.reportWidgets.clearCache.label',
                'context' => 'dashboard'
            ]
        ];
    }

    public function registerConsoleCommands(): void
    {
        $this->registerConsoleCommand('clear-upload-image-thumbs', '\Webmaxx\Cache\Console\ClearUploadImageThumbs');
    }
}
