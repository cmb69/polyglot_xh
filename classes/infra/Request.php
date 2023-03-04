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

namespace Polyglot\Infra;

use Plib\Url;

class Request
{
    public static function current(): self
    {
        return new self;
    }

    public function url(): Url
    {
        $base = preg_replace(['/index\.php$/', "/(?<=\\/){$this->sl()}\\/$/"], "", CMSIMPLE_URL);
        assert($base !== null);
        return new Url($base, $this->sl() === $this->defaultLanguage() ? "" : $this->sl(), $this->su());
    }

    /** @codeCoverageIgnore */
    protected function defaultLanguage(): string
    {
        global $cf;
        return $cf["language"]["default"];
    }

    /** @codeCoverageIgnore */
    public function sl(): string
    {
        global $sl;
        return $sl;
    }

    /** @codeCoverageIgnore */
    public function s(): int
    {
        global $s;
        return $s;
    }

    /** @codeCoverageIgnore */
    protected function su(): string
    {
        global $su;
        return $su;
    }
}
