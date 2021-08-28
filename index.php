<?php

/**
 * Copyright 2012-2017 Christoph M. Becker
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

/**
 * The plugin version.
 */
define('POLYGLOT_VERSION', '1.0beta2');

/**
 * Returns the language menu.
 *
 * @return string (X)HTML.
 */
function Polyglot_languageMenu()
{
    ob_start();
    (new Polyglot\LanguageMenuController)->defaultAction();
    return (string) ob_get_clean();
}

(new Polyglot\Plugin)->run();
