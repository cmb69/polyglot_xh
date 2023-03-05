<?php

/**
 * Copyright 2021-2023 Christoph M. Becker
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

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\HtmlView as View;
use Polyglot\Infra\FakeLanguageRepo;
use Polyglot\Infra\FakePages;
use Polyglot\Infra\FakeRequest;
use Polyglot\Infra\FakeTranslationRepo;
use Polyglot\Value\Translation;

class TranslationsTest extends TestCase
{
    public function testRendersTranslations(): void
    {
        $sut = $this->sut();
        $response = $sut(new FakeRequest(["sl" => "en", "defaultLanguage" => "en"]));
        $this->assertEquals("Polyglot – Translations", $response->title());
        Approvals::verifyHtml($response->output());
    }

    private function sut(): Translations
    {
        return new Translations(
            $this->conf(),
            new FakePages,
            new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["polyglot"]),
            new FakeLanguageRepo(["second" => ["de", "fr"]]),
            new FakeTranslationRepo(["trans" => [
                0 => new Translation("foo", ["de" => "foo-de"]),
                1 => new Translation("bar", ["fr" => "bar-fr"]),
            ]])
        );
    }

    private function conf(): array
    {
        return XH_includeVar("./config/config.php", "plugin_cf")["polyglot"] + ["language_default" => "en"];
    }
}
