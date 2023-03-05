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

namespace Polyglot\Logic;

class Util
{
    /** @return array<string,array{translated:string,untranslated:string|null}> */
    public static function parseLanguageLabels(string $labels): array
    {
        $lines = preg_split('/\R/u', $labels);
        if ($lines === false) {
            return [];
        }
        $res = [];
        foreach ($lines as $line) {
            $parts = explode("=", $line, 2);
            if (count($parts) < 2) {
                continue;
            }
            [$language, $text] = $parts;
            $parts = explode(";", $text, 2);
            $res[$language]["translated"] = $parts[0];
            $res[$language]["untranslated"] = $parts[1] ?? null;
        }
        return $res;
    }
}
