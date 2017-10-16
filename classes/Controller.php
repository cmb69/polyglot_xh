<?php

/**
 * Copyright 2012-2017 Christoph M. Becker
 *
 * This file is part of Polyglott_XH.
 *
 * Polyglott_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Polyglott_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Polyglott_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Polyglott;

/**
 * The controller class.
 *
 * @category CMSimple_XH
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class Controller
{
    /**
     * The model instance.
     *
     * @var object
     */
    protected $model;

    /**
     * Initializes a new instance.
     */
    public function __construct()
    {
        global $pth, $sl, $cf;

        $this->model = new Model(
            $sl,
            $cf['language']['default'],
            $pth['folder']['base'],
            $pth['folder']['plugins'] . 'polyglott/cache/'
        );
    }

    /**
     * Dispatches according to request.
     *
     * @return void
     */
    public function dispatch()
    {
        global $hjs, $pd_router;

        $this->updateCache();
        $pd_router->add_interest('polyglott_tag');
        if (defined('XH_ADM') && XH_ADM) {
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(true);
            }
            $this->addPageDataTab();
            if (XH_wantsPluginAdministration('polyglott')) {
                $this->handleAdministration();
            }
        }
        $hjs .= $this->alternateLinks();
    }

    /**
     * Returns whether the cache is stale.
     *
     * @return bool
     */
    private function isCacheStale()
    {
        global $pth;

        $contentLastMod = filemtime($pth['file']['content']);
        $pageDataLastMod = file_exists($pth['file']['pagedata'])
            ? filemtime($pth['file']['pagedata'])
            : 0;
        $tagsLastMod = $this->model->lastMod();
        return $tagsLastMod < max($contentLastMod, $pageDataLastMod);
    }

    /**
     * Updates the cache.
     *
     * @return void
     */
    private function updateCache()
    {
        global $u, $pd_router;

        $needsUpdate = $this->isCacheStale();
        if ($this->model->init($needsUpdate)) {
            if ($needsUpdate) {
                if (!$this->model->update($pd_router->find_all(), $u)) {
                    e('cntsave', 'file', $this->model->tagsFile());
                }
            }
        } else {
            e('cntopen', 'file', $this->model->tagsFile());
        }
    }

    /**
     * Adds the page data tab.
     *
     * @return void
     */
    private function addPageDataTab()
    {
        global $pth, $pd_router, $plugin_tx;

        $pd_router->add_tab(
            $plugin_tx['polyglott']['label_tab'],
            $pth['folder']['plugins'] . 'polyglott/polyglott_view.php'
        );
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     */
    private function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('on');
        switch ($admin) {
            case '':
                $o .= $this->info();
                break;
            case 'plugin_main':
                $o .= $this->administration();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'polyglott');
        }
    }

    /**
     * Returns the alternate hreflang links.
     *
     * @return string (X)HTML.
     */
    private function alternateLinks()
    {
        global $s;

        $res = '';
        $tag = $this->pageTag($s);
        foreach ($this->model->languages() as $language) {
            if ($this->model->isTranslated($tag, $language)) {
                $res .= $this->alternateLinksFor($language, $tag);
            }
        }
        return $res;
    }

    /**
     * Returns the alternate hreflang links for a single language.
     *
     * @param string $language An ISO 639-1 language code.
     * @param string $tag      A polyglott tag.
     *
     * @return string (X)HTML.
     */
    private function alternateLinksFor($language, $tag)
    {
        global $cf;

        $html = '';
        $href = $this->model->languageURL($language, $tag);
        if ($language == $cf['language']['default']) {
            $html .= $this->renderAlternateLink('x-default', $href) . PHP_EOL;
        }
        $html .= $this->renderAlternateLink($language, $href) . PHP_EOL;
        return $html;
    }

    /**
     * Renders an alternate hreflang link.
     *
     * @param string $hreflang A hreflang value.
     * @param string $href     A href value.
     *
     * @return string (X)HTML.
     */
    private function renderAlternateLink($hreflang, $href)
    {
        return tag(
            'link rel="alternate" hreflang="' . XH_hsc($hreflang)
            . '" href="' . XH_hsc($href) . '"'
        );
    }

    /**
     * Returns the plugin information view.
     *
     * @return string (X)HTML.
     */
    private function info()
    {
        global $pth;

        $view = new View('info');
        $view->checks = (new SystemCheckService)->getChecks();
        $view->icon = $pth['folder']['plugins'] . 'polyglott/polyglott.png';
        $view->version = POLYGLOTT_VERSION;
        return (string) $view;
    }

    /**
     * Returns a polyglott tag.
     *
     * @param int $index The index of the page.
     *
     * @return string
     */
    private function pageTag($index)
    {
        global $pd_router;

        $pageData = $pd_router->find_page($index);
        $res = isset($pageData['polyglott_tag'])
            ? $pageData['polyglott_tag']
            : null;
        return $res;
    }

    /**
     * Returns the main administration view.
     *
     * @return string (X)HTML.
     */
    private function administration()
    {
        global $sn, $cl, $h, $l, $u;

        $languages = $this->model->otherLanguages();
        $pages = array();
        for ($i = 0; $i < $cl; $i++) {
            $heading = $h[$i];
            $url = $sn . '?' . $u[$i] . '&amp;edit';
            $indent = $l[$i] - 1;
            $tag = $this->pageTag($i);
            $translations = array();
            foreach ($languages as $language) {
                $translations[$language]
                    = $this->model->isTranslated($tag, $language)
                        ? $this->model->languageURL($language, $tag) . '&amp;edit'
                        : null;
            }
            $pages[] = compact('heading', 'url', 'indent', 'tag', 'translations');
        }
        $view = new View('admin');
        $view->languages = $languages;
        $view->pages = $pages;
        return (string) $view;
    }
}
