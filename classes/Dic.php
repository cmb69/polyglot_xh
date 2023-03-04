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
use Polyglot\Infra\LanguageRepo;
use Polyglot\Infra\Model;
use Polyglot\Infra\Pages;
use Polyglot\Infra\SystemChecker;
use Polyglot\Infra\TranslationRepo;

class Dic
{
    public static function makeAlternateLinkController(): AlternateLinkController
    {
        global $cf;

        return new AlternateLinkController(
            $cf['language']['default'],
            self::makeView(),
            new LanguageRepo,
            self::makeTranslationRepo()
        );
    }

    public static function makeLanguageMenuController(): LanguageMenuController
    {
        global $pth, $cf, $plugin_cf;

        return new LanguageMenuController(
            $cf['language']['default'],
            $pth['folder']['flags'],
            $plugin_cf['polyglot']['flags_extension'],
            $plugin_cf['polyglot']['languages_labels'],
            self::makeView(),
            new LanguageRepo,
            self::makeTranslationRepo()
        );
    }

    public static function makePageDataTabController(): PageDataTabController
    {
        return new PageDataTabController(self::makeView());
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
        global $cf;

        return new MainAdminController(
            $cf['language']['default'],
            new Pages(),
            self::makeView(),
            new LanguageRepo,
            self::makeTranslationRepo()
        );
    }

    private static function makeTranslationRepo(): TranslationRepo
    {
        global $pth;

        return new TranslationRepo($pth['folder']['plugins'] . 'polyglot/cache/', new Pages());
    }

    private static function makeView(): View
    {
        global $pth, $plugin_tx;

        return new View("{$pth["folder"]["plugins"]}polyglot/views", $plugin_tx["polyglot"]);
    }
}
