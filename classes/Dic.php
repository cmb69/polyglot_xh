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
use Polyglot\Infra\Model;
use Polyglot\Infra\Pages;
use Polyglot\Infra\SystemChecker;

class Dic
{
    public static function makeAlternateLinkController(): AlternateLinkController
    {
        global $cf, $s;

        return new AlternateLinkController($cf['language']['default'], $s, self::makeModel(), self::makeView());
    }

    public static function makeLanguageMenuController(): LanguageMenuController
    {
        global $pth, $plugin_cf, $s;

        return new LanguageMenuController(
            $pth['folder']['flags'],
            $plugin_cf['polyglot']['flags_extension'],
            $plugin_cf['polyglot']['languages_labels'],
            $s,
            self::makeModel(),
            self::makeView()
        );
    }

    public static function makePageDataTabController(): PageDataTabController
    {
        return new PageDataTabController(self::makeUrl(), self::makeView());
    }

    public static function makeInfoController(): InfoController
    {
        global $pth, $plugin_tx;

        return new InfoController(
            $pth["folder"]["plugin"],
            $plugin_tx["polyglot"],
            new SystemChecker,
            self::makeView()
        );
    }

    public static function makeMainAdminController(): MainAdminController
    {
        return new MainAdminController(
            new Pages(),
            self::makeUrl(),
            self::makeModel(),
            self::makeView()
        );
    }

    private static function makeModel(): Model
    {
        global $pth, $sl, $cf;

        return new Model(
            $sl,
            $cf['language']['default'],
            XH_secondLanguages(),
            $pth['folder']['plugins'] . 'polyglot/cache/',
            $pth['file']['content'],
            self::makeUrl(),
            new Pages()
        );
    }

    private static function makeView(): View
    {
        global $pth, $plugin_tx;

        return new View("{$pth["folder"]["plugins"]}polyglot/views", $plugin_tx["polyglot"]);
    }

    private static function makeUrl(): Url
    {
        global $sl, $cf, $su;

        $base = preg_replace(['/index\.php$/', "/(?<=\\/)$sl\\/$/"], "", CMSIMPLE_URL);
        assert($base !== null);
        return new Url($base, $sl === $cf["language"]["default"] ? "" : $sl, $su);
    }
}
