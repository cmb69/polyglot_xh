<?php

/**
 * Copyright 2012-2017 Christoph M. Becker
 *
 * This file is part of Polyglot_XH.
 *
 * Polyglot_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Polyglot_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Polyglot_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Polyglot;

class Plugin
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
            $pth['folder']['plugins'] . 'polyglot/cache/'
        );
    }

    /**
     * Dispatches according to request.
     *
     * @return void
     */
    public function run()
    {
        global $pd_router;

        $this->updateCache();
        $pd_router->add_interest('polyglot_tag');
        if (defined('XH_ADM') && XH_ADM) {
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(true);
            }
            $this->addPageDataTab();
            if (XH_wantsPluginAdministration('polyglot')) {
                $this->handleAdministration();
            }
        }
        (new AlternateLinkController($this->model))->defaultAction();
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
            $plugin_tx['polyglot']['label_tab'],
            $pth['folder']['plugins'] . 'polyglot/polyglot_view.php'
        );
    }

    /**
     * @return void
     */
    private function handleAdministration()
    {
        global $admin, $o;

        $o .= print_plugin_admin('on');
        switch ($admin) {
            case '':
                ob_start();
                $this->renderInfo();
                $o .= (string) ob_get_clean();
                break;
            case 'plugin_main':
                ob_start();
                (new MainAdminController($this->model))->defaultAction();
                $o .= ob_get_clean();
                break;
            default:
                $o .= plugin_admin_common();
        }
    }

    /**
     * @return void
     */
    private function renderInfo()
    {
        global $pth;

        $view = new View('info');
        $view->checks = (new SystemCheckService)->getChecks();
        $view->icon = $pth['folder']['plugins'] . 'polyglot/polyglot.png';
        $view->version = POLYGLOT_VERSION;
        $view->render();
    }
}
