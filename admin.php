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

use Polyglot\Dic;
use Polyglot\Infra\Request;
use XH\PageDataRouter;

if (!defined("CMSIMPLE_XH_VERSION")) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

/**
 * @var array{folder:array<string,string>,file:array<string,string>} $pth
 * @var array<string,array<string,string>> $plugin_tx
 * @var PageDataRouter $pd_router
 * @var string $admin
 * @var string $o
 */

XH_registerStandardPluginMenuItems(true);

$pd_router->add_tab(
    $plugin_tx["polyglot"]["label_tab"],
    $pth["folder"]["plugins"] . "polyglot/polyglot_view.php"
);

if (XH_wantsPluginAdministration("polyglot")) {
    $o .= print_plugin_admin("on");
    switch ($admin) {
        case "":
            $o .= Dic::makeInfoController()->defaultAction();
            break;
        case "plugin_main":
            $o .= Dic::makeMainAdminController()->defaultAction(Request::current());
            break;
        default:
            $o .= plugin_admin_common();
    }
}
