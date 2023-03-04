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

class FakeRequest extends Request
{
    private $options;

    public function __construct($options = [])
    {
        $this->options = $options;
    }

    protected function defaultLanguage(): string
    {
        return $this->options["defaultLanguage"] ?? "";
    }

    public function sl(): string
    {
        return $this->options["sl"] ?? "";
    }

    public function s(): int
    {
        return $this->options["s"] ?? 0;
    }

    protected function su(): string
    {
        return $this->options["su"] ?? "";
    }
}
