<?php

/**
 * Copyright 2023 Christoph M. Becker
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

use PHPUnit\Framework\TestCase;

class DicTest extends TestCase
{
    public function setUp(): void
    {
        global $pth, $cf, $plugin_cf, $plugin_tx, $c;

        $pth = [
            "folder" => ["base" => "", "flags" => "", "plugin" => "", "plugins" => ""],
            "file" => ["content" => ""]
        ];
        $cf = ["language" => ["default" => ""]];
        $plugin_cf = ["polyglot" => ["flags_extension" => "", "languages_labels" => ""]];
        $plugin_tx = ["polyglot" => []];
        $c = [];
    }

    public function testMakesAlternateLinks(): void
    {
        $this->assertInstanceOf(AlternateLinks::class, Dic::makeAlternateLinks());
    }

    public function testMakesLanguageMenu(): void
    {
        $this->assertInstanceOf(LanguageMenu::class, Dic::makeLanguageMenu());
    }

    public function testMakesPageDataTab(): void
    {
        $this->assertInstanceOf(PageDataTab::class, Dic::makePageDataTab());
    }

    public function testMakesPluginInfo(): void
    {
        $this->assertInstanceOf(PluginInfo::class, Dic::makePluginInfo());
    }

    public function testMakesTranslations(): void
    {
        $this->assertInstanceOf(Translations::class, Dic::makeTranslations());
    }
}
