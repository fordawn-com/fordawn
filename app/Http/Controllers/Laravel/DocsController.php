<?php

namespace App\Http\Controllers\Laravel;

use App\Documentation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Crawler;

class DocsController extends Controller
{
    protected $docs;

    public function __construct(Documentation $docs)
    {
        $this->docs = $docs;
    }

    public function showRootPage()
    {
        return redirect('laravel/'. DEFAULT_LARAVEL_VERSION);
    }

    public function showPage($version, $page = null)
    {
        if ( ! $this->isVersion($version)) {
            return redirect('laravel/'.DEFAULT_LARAVEL_VERSION.'/'.$version);
        }

        if ( ! defined('CURRENT_LARAVEL_VERSION')) {

        }

        $sectionPage = $page ?? 'installation';
        $content = $this->docs->get($version, $sectionPage);

        if (is_null($content)) {
            abort(404);
        }

        $title = (new Crawler($content))->filterXPath('//h1');

        $section = '';
    }

    protected function isVersion($version)
    {
        return in_array($version, array_keys(Documentation::getDocVersions()));
    }
}
