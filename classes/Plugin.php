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

class Plugin
{
    /**
     * @return void
     */
    public function run()
    {
        global $pd_router;

        (new CacheController)->defaultAction();
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
        (new AlternateLinkController)->defaultAction();
    }

    /**
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
                ob_start();
                (new MainAdminController)->defaultAction();
                $o .= ob_get_clean();
                break;
            default:
                $o .= plugin_admin_common($action, $admin, 'polyglott');
        }
    }

    /**
     * @return string
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
}
