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

use Plib\HtmlView as View;
use Plib\Url;
use XH\Pages;

class Plugin
{
    const VERSION = "1.0beta2";

    /**
     * @return void
     */
    public static function run()
    {
        global $cf, $s, $pd_router;

        $pd_router->add_interest('polyglot_tag');
        if (defined('XH_ADM') && XH_ADM) {
            XH_registerStandardPluginMenuItems(true);
            self::addPageDataTab();
            if (XH_wantsPluginAdministration('polyglot')) {
                self::handleAdministration();
            }
        }
        (new AlternateLinkController($cf['language']['default'], $s, self::getModel(), self::view()))->defaultAction();
    }

    /**
     * @return void
     */
    private static function addPageDataTab()
    {
        global $pth, $pd_router, $plugin_tx;

        $pd_router->add_tab(
            $plugin_tx['polyglot']['label_tab'],
            "{$pth['folder']['plugins']}polyglot/polyglot_view.php"
        );
    }

    /**
     * @return void
     */
    private static function handleAdministration()
    {
        global $pth, $plugin_tx, $admin, $o;

        $o .= print_plugin_admin('on');
        switch ($admin) {
            case '':
                ob_start();
                $controller = new InfoController(
                    new SystemCheckService("{$pth['folder']['plugins']}polyglot", $plugin_tx['polyglot']),
                    self::view()
                );
                $controller->defaultAction();
                $o .= (string) ob_get_clean();
                break;
            case 'plugin_main':
                ob_start();
                (new MainAdminController(new Pages(), self::url(), self::getModel(), self::view()))->defaultAction();
                $o .= ob_get_clean();
                break;
            default:
                $o .= plugin_admin_common();
        }
    }

    public static function languageMenu(): string
    {
        global $pth, $plugin_cf, $s;

        $controller = new LanguageMenuController(
            $pth['folder']['flags'],
            $plugin_cf['polyglot']['flags_extension'],
            $plugin_cf['polyglot']['languages_labels'],
            $s,
            self::getModel(),
            self::view()
        );
        ob_start();
        $controller->defaultAction();
        return (string) ob_get_clean();
    }

    /**
     * @param array<string,string> $pageData
     */
    public static function pageDataView(array $pageData): string
    {
        $command = new PageDataTabController($pageData, self::url(), self::view());
        ob_start();
        $command->defaultAction();
        return (string) ob_get_clean();
    }

    private static function url(): Url
    {
        global $sl, $cf, $su;

        $base = preg_replace(['/index\.php$/', "/(?<=\\/)$sl\\/$/"], "", CMSIMPLE_URL);
        assert($base !== null);
        return new Url($base, $sl === $cf["language"]["default"] ? "" : $sl, $su);
    }

    private static function getModel(): Model
    {
        global $pth, $sl, $cf, $pd_router, $u;

        return new Model(
            $sl,
            $cf['language']['default'],
            XH_secondLanguages(),
            $pth['folder']['plugins'] . 'polyglot/cache/',
            $pd_router,
            $u,
            $pth['file']['content'],
            self::url()
        );
    }

    private static function view(): View
    {
        global $pth, $plugin_tx;

        return new View("{$pth['folder']['plugins']}polyglot/views", $plugin_tx["polyglot"]);
    }
}
