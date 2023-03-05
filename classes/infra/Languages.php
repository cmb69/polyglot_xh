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

namespace Polyglot\Infra;

class Languages
{
    /** @return list<string> */
    public function all(): array
    {
        $languages = $this->secondLanguages();
        $languages[] = $this->defaultLanguage();
        sort($languages);
        return $languages;
    }

    /** @return list<string> */
    public function others(string $that): array
    {
        $result = [];
        foreach ($this->all() as $language) {
            if ($language !== $that) {
                $result[] = $language;
            }
        }
        return $result;
    }

    /** @codeCoverageIgnore */
    protected function defaultLanguage(): string
    {
        global $cf;
        return $cf["language"]["default"];
    }

    /**
     * @return list<string>
     * @codeCoverageIgnore
     */
    protected function secondLanguages(): array
    {
        return XH_secondLanguages();
    }
}
