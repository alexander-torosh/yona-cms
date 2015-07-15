<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Plugin;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use Seo\Model\Manager;

/**
 * Class SeoManagerPlugin
 * @package Seo\Plugin
 * @variable $matched_route \Seo\Model\Manager
 */
class SeoManager extends Plugin
{

    const CACHE_LIFETIME = 120;

    public function __construct(Dispatcher $dispatcher, Request $request, Router $router, View $view)
    {
        if ($view->getLayout() == 'admin') {
            return;
        }

        $match_url_entry = $this->matchingUrl($request->getURI());
        if ($match_url_entry) {
            $this->pick($match_url_entry);
        }
    }

    private function matchingUrl($url)
    {
        $urls = Manager::urls();
        if (array_key_exists($url, $urls)) {
            return $urls[$url];
        }

    }

    private function pick($entry)
    {
        $helper = $this->getDI()->get('helper');
        $view = $this->getDi()->get('view');

        if ($entry['head_title']) {
            $helper->title()->set($entry['head_title']);
        }
        if ($entry['meta_description']) {
            $helper->meta()->set('description', $entry['meta_description']);
        }
        if ($entry['meta_keywords']) {
            $helper->meta()->set('keywords', $entry['meta_keywords']);
        }
        if ($entry['seo_text']) {
            $view->seo_text = $entry['seo_text'];
        }

        $helper->meta()->set('seo-manager', 'matched');
    }

} 