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

namespace Polyglot\Logic;

use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    /** @dataProvider languageLabels */
    public function testParsesLanguageLabels(string $labels, array $expected): void
    {
        $actual = Util::parseLanguageLabels($labels);
        $this->assertEquals($expected, $actual);
    }

    public function languageLabels(): array
    {
        return [
            "empty" => [
                "",
                []
            ],
            "translated only" => [
                "en=English translation",
                ["en" => ["translated" => "English translation", "untranslated" => null]]
            ],
            "default" => [
                "en=English translation;English website\nde=deutsche Übersetzung;deutsche Website",
                [
                    "en" => ["translated" => "English translation", "untranslated" => "English website"],
                    "de" => ["translated" => "deutsche Übersetzung", "untranslated" => "deutsche Website"],
                ],
            ],
            "bad UTF-8" => [
                "\x80",
                [],
            ],
        ];
    }
}
