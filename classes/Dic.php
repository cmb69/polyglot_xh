<?php

/**
 * Copyright 2012-2023 Christoph M. Becker
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
use Polyglot\Infra\ContentReader;
use Polyglot\Infra\Languages;
use Polyglot\Infra\Pages;
use Polyglot\Infra\Repository;
use Polyglot\Infra\SystemChecker;

class Dic
{
    public static function makeAlternateLinks(): AlternateLinks
    {
        return new AlternateLinks(
            self::makeConf(),
            self::makeRepository(),
            self::makeView()
        );
    }

    public static function makeLanguageMenu(): LanguageMenu
    {
        global $pth;

        return new LanguageMenu(
            self::makeConf(),
            $pth['folder']['flags'],
            self::makeRepository(),
            self::makeView()
        );
    }

    public static function makePageDataTab(): PageDataTab
    {
        return new PageDataTab(self::makeView());
    }

    public static function makePluginInfo(): PluginInfo
    {
        global $pth;

        return new PluginInfo(
            $pth["folder"]["plugin"],
            new SystemChecker,
            self::makeView()
        );
    }

    public static function makeTranslations(): Translations
    {
        return new Translations(
            self::makeConf(),
            new Pages(),
            self::makeRepository(),
            self::makeView()
        );
    }

    /** @return array<string,string> */
    private static function makeConf(): array
    {
        global $cf, $plugin_cf;

        return $plugin_cf["polyglot"] + ["language_default" => $cf["language"]["default"]];
    }

    private static function makeRepository(): Repository
    {
        global $pth, $cf;

        return new Repository(
            $pth['folder']['plugins'] . 'polyglot/cache/translations.dat',
            $pth["folder"]["content"] . $pth["folder"]["base"],
            $cf["language"]["default"],
            new Pages(),
            new Languages,
            new ContentReader($cf["language"]["default"])
        );
    }

    private static function makeView(): View
    {
        global $pth, $plugin_tx;

        return new View("{$pth["folder"]["plugins"]}polyglot/views", $plugin_tx["polyglot"]);
    }
}
