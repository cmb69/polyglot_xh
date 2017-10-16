<?php

/**
 * Copyright 2012-2017 Christoph M. Becker
 *
 * This file is part of Polyglott_XH.
 *
 * Polyglott_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Polyglott_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Polyglott_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Returns the page data tab view.
 *
 * @param array $pageData The page data of the current page.
 *
 * @return string The (X)HTML.
 */
function Polyglott_view(array $pageData)
{
    $command = new Polyglott\PageDataTabController($pageData);
    ob_start();
    $command->defaultAction();
    return ob_get_clean();
}
