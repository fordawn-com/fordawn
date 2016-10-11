<?php

namespace App;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Cache\Repository as Cache;

class Documentation
{
    protected $files;

    protected $cache;

    public function __construct(Filesystem $files, Cache $cache)
    {
        $this->files = $files;
        $this->cache = $cache;
    }

    /**
     * Get the documentation index page.
     */
    public function getIndex($version)
    {
        return $this->cache->remember('laravel.'.$version.'.index', 60,
            function () use ($version){
                $path = resource_path('docs/laravel/'.$version.'/documentation.md');

                if ($this->files->exists($path)) {
                    return $this->replaceLinks($version, (new \ParsedownExtra)->text($this->files->get($path)));
                }

                return null;
            }
        );
    }

    /**
     * Get the given documentation page.
     */
    public function get($version, $page)
    {
        return $this->cache->remember('laravel.'.$version.'.'.$page, 60,
            function () use ($version, $page) {
                $path = base_path('resources/docs/laravel/'.$version.'/'.$page.'.md');

                if ($this->files->exists($path)) {
                    return $this->replaceLinks($version, (new \ParsedownExtra)->text($this->files->get($path)));
                }

                return null;
            }
        );
    }

    /**
     * Replace the version place-holder in links.
     */
    protected function replaceLinks($version, $content)
    {
        return str_replace('{{version}}', $version, $content);
    }

    /**
     * Get the publicly available versions of the documentation
     */
    public static function getDocVersions()
    {
        return [
            '5.3' => '5.3',
        ];
    }

    /**
     * Check if the given section exists.
     */
    public function sectionExists($version, $page)
    {
        return $this->files->exists(
            base_path('resources/docs/laravel/'.$version.'/'.$page.'.md')
        );
    }
}
