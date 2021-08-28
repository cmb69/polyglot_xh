<?php

/**
 * Copyright 2012-2021 Christoph M. Becker
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
    const VERSION = "1.0beta2";

    /**
     * @return void
     */
    public static function run()
    {
        global $pd_router;

        $pd_router->add_interest('polyglot_tag');
        if (defined('XH_ADM') && XH_ADM) {
            XH_registerStandardPluginMenuItems(true);
            self::addPageDataTab();
            if (XH_wantsPluginAdministration('polyglot')) {
                self::handleAdministration();
            }
        }
        (new AlternateLinkController(self::getModel()))->defaultAction();
    }

    /**
     * @return void
     */
    private static function addPageDataTab()
    {
        global $pth, $pd_router, $plugin_tx;

        $pd_router->add_tab(
            $plugin_tx['polyglot']['label_tab'],
            "{$pth['folder']['plugins']}admin.php"
        );
    }

    /**
     * @return void
     */
    private static function handleAdministration()
    {
        global $admin, $o;

        $o .= print_plugin_admin('on');
        switch ($admin) {
            case '':
                ob_start();
                self::renderInfo();
                $o .= (string) ob_get_clean();
                break;
            case 'plugin_main':
                ob_start();
                (new MainAdminController(self::getModel()))->defaultAction();
                $o .= ob_get_clean();
                break;
            default:
                $o .= plugin_admin_common();
        }
    }

    /**
     * @return void
     */
    private static function renderInfo()
    {
        $view = new View('info');
        $view->render([
            'checks' => (new SystemCheckService)->getChecks(),
            'version' => self::VERSION,
        ]);
    }

    /**
     * @return string
     */
    public static function languageMenu()
    {
        ob_start();
        (new LanguageMenuController(self::getModel()))->defaultAction();
        return (string) ob_get_clean();
    }

    /**
     * @param array<string,string> $pageData
     * @return string
     */
    public static function pageDataView(array $pageData)
    {
        $command = new PageDataTabController($pageData);
        ob_start();
        $command->defaultAction();
        return (string) ob_get_clean();
    }

    /**
     * @return Model
     */
    private static function getModel()
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
