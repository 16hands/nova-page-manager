<?php

namespace OptimistDigital\NovaPageManager;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use OptimistDigital\NovaPageManager\Models\Page;

class NovaPageManager extends Tool
{
    private static $templates = [];
    private static $locales = [];
    private static $draftEnabled = false;

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-page-manager', __DIR__ . '/../dist/js/page-manager-tool.js');
        Nova::script('nova-template-field', __DIR__ . '/../dist/js/template-field.js');
        Nova::script('nova-parent-field', __DIR__ . '/../dist/js/parent-field.js');
        Nova::script('nova-region-field', __DIR__ . '/../dist/js/region-field.js');
        Nova::script('nova-published-field', __DIR__ . '/../dist/js/published-field.js');
        Nova::script('nova-draft-button', __DIR__ . '/../dist/js/draft-button.js');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-page-manager::navigation');
    }

    public static function configure(array $data = [])
    {
        self::$templates = isset($data['templates']) && is_array($data['templates']) ? $data['templates'] : [];
        self::$locales = isset($data['locales']) && is_array($data['locales']) ? $data['locales'] : ['en_US' => 'English'];
        self::$draftEnabled = isset($data['draft']) && $data['draft'] === true;
    }

    public static function getTemplates(): array
    {
        return array_filter(self::$templates, function ($template) {
            return class_exists($template);
        });
    }

    public static function getPageTemplates(): array
    {
        return array_filter(self::getTemplates(), function ($template) {
            return $template::$type === 'page';
        });
    }

    public static function getRegionTemplates(): array
    {
        return array_filter(self::getTemplates(), function ($template) {
            return $template::$type === 'region';
        });
    }

    public static function getLocales(): array
    {
        return self::$locales;
    }

    public static function getPagesTableName(): string
    {
        return config('nova-page-manager.table', 'nova_page_manager') . '_pages';
    }

    public static function getRegionsTableName(): string
    {
        return config('nova-page-manager.table', 'nova_page_manager') . '_regions';
    }

    public static function getPageUrl(Page $page)
    {
        $getPageUrl = config('nova-page-manager.page_url');
        if ($getPageUrl) {
            return $getPageUrl($page);
        }
        return null;
    }

    public static function draftEnabled(): bool
    {
        return self::$draftEnabled;
    }
}
