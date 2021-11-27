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
    /**
     * @var string
     */
    private $template;

    /**
     * @var array<string,mixed>
     */
    private $data = array();

    public function text(string $key): string
    {
        global $plugin_tx;

        $args = func_get_args();
        array_shift($args);
        return $this->escape(vsprintf($plugin_tx['polyglot'][$key], $args));
    }

    public function plural(string $key, int $count): string
    {
        global $plugin_tx;

        if ($count == 0) {
            $key .= '_0';
        } else {
            $key .= XH_numberSuffix($count);
        }
        $args = func_get_args();
        array_shift($args);
        return $this->escape(vsprintf($plugin_tx['polyglot'][$key], $args));
    }

    /**
     * @param array<string,mixed> $data
     * @return void
     */
    public function render(string $template, array $data)
    {
        global $pth;

        $this->template = "{$pth['folder']['plugins']}polyglot/views/{$template}.php";
        $this->data = $data;
        echo "<!-- {$template} -->\n";
        unset($template, $data, $pth);
        extract($this->data);
        include $this->template;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function escape($value)
    {
        if ($value instanceof HtmlString) {
            return $value;
        } else {
            return XH_hsc($value);
        }
    }
}
