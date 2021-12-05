<?php

/**
 * Copyright 2021 Christoph M. Becker
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

final class Url
{
    /** @var string */
    private $base;

    /** @var string */
    private $page;

    /** @var array<string,string> */
    private $params = [];

    public function __construct(string $base, string $page = "")
    {
        $this->base = $base;
        $this->page = $page;
    }

    public function page(string $page): self
    {
        $url = clone $this;
        $url->page = $page;
        return $url;
    }

    public function param(string $name, string $value = ""): self
    {
        $url = clone $this;
        $url->params[$name] = $value;
        return $url;
    }

    public function string(): string
    {
        $query = $this->page;
        if (count($this->params) > 0) {
            $rest = http_build_query($this->params, "", "&", PHP_QUERY_RFC3986);
            $query .= "&" . preg_replace('/=(?=&|$)/', "", $rest);
        }
        $result = $this->base;
        if ($query !== "") {
            $result .= "?$query";
        }
        return $result;
    }
}
