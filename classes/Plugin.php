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
     * Dispatches according to request.
     *
     * @return void
     */
    public function run()
    {
        global $pd_router;

        $pd_router->add_interest('polyglot_tag');
        if (defined('XH_ADM') && XH_ADM) {
            XH_registerStandardPluginMenuItems(true);
            $this->addPageDataTab();
            if (XH_wantsPluginAdministration('polyglot')) {
                $this->handleAdministration();
            }
        }
        (new AlternateLinkController($this->getModel()))->defaultAction();
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
                (new MainAdminController($this->getModel()))->defaultAction();
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
        $view = new View('info');
        $view->render([
            'checks' => (new SystemCheckService)->getChecks(),
            'version' => POLYGLOT_VERSION,
        ]);
    }

    /**
     * @return Model
     */
    private function getModel()
    {
        global $pth, $sl, $cf, $pd_router, $u;

        return new Model(
            $sl,
            $cf['language']['default'],
            $pth['folder']['plugins'] . 'polyglot/cache/',
            $pd_router,
            $u,
            $pth['file']['content']
        );
    }
}
