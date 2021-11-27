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

class View
{
    /** @var string */
    private $templateDir;

    /** @var array<string,string> */
    private $lang;

    /**
     * @param array<string,string> $lang
     */
    public function __construct(string $templateDir, array $lang)
    {
        $this->templateDir = $templateDir;
        $this->lang = $lang;
    }

    /**
     * @param string|HtmlString $args
     */
    public function text(string $key, ...$args): string
    {
        $args = array_map([$this, "esc"], $args);
        return sprintf($this->esc($this->lang[$key]), ...$args);
    }

    /**
     * @param string|HtmlString $args
     */
    public function plural(string $key, int $count, ...$args): string
    {
        if ($count == 0) {
            $key .= '_0';
        } else {
            $key .= XH_numberSuffix($count);
        }
        $args = array_map([$this, "esc"], $args);
        return sprintf($this->esc($this->lang[$key]), $count, ...$args);
    }

    /**
     * @param array<string,mixed> $_data
     * @return void
     */
    public function render(string $_template, array $_data)
    {
        extract($_data);
        include "{$this->templateDir}/{$_template}.php";
    }

    /**
     * @param string|HtmlString $value
     * @return string
     */
    public function esc($value)
    {
        if ($value instanceof HtmlString) {
            return $value->asString();
        } else {
            return XH_hsc($value);
        }
    }
}
