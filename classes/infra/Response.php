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

class Response
{
    public static function create(string $output): self
    {
        $that = new self;
        $that->output = $output;
        return $that;
    }

    /** @var string */
    private $output = "";

    /** @var string|null */
    private $title = null;

    /** @var string|null */
    private $hjs = null;

    public function withTitle(string $title): self
    {
        $that = clone $this;
        $that->title = $title;
        return $that;
    }

    public function addHjs(string $hjs): self
    {
        $that = clone $this;
        $that->hjs .= $hjs;
        return $that;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function hjs(): ?string
    {
        return $this->hjs;
    }

    /** @return string */
    public function respond()
    {
        global $title, $hjs;

        if ($this->title !== null) {
            $title = $this->title;
        }
        if ($this->hjs !== null) {
            $hjs .= $this->hjs;
        }
        return $this->output;
    }
}
