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

        if ($this->docs->sectionExists($version, $page)) {
            $section .= '/'.$page;
        } elseif ( ! is_null($page)) {
            return redirect('laravel/'.$version);
        }

        $canonical = null;

        if ($this->docs->sectionExists(DEFAULT_LARAVEL_VERSION, $sectionPage)) {
            $canonical = 'docs/laravel/'.DEFAULT_LARAVEL_VERSION.'/'.$sectionPage;
        }

        return view('laravel.docs', [
            'title' => count($title) ? $title->text() : null,
            'index' => $this->docs->getIndex($version),
            'content' => $content,
            'currentVersion' => $version,
            'versions' => Documentation::getDocVersions(),
            'currentSection' => $section,
            'canonical' => $canonical,
        ]);
    }

    protected function isVersion($version)
    {
        return in_array($version, array_keys(Documentation::getDocVersions()));
    }
}
