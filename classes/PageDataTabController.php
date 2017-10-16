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

namespace Polyglott;

/**
 * The page data tab commands.
 *
 * @category CMSimple_XH
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class PageDataTabController
{
    /**
     * The page data.
     *
     * @var array
     */
    private $pageData;

    /**
     * Initializes a new instance.
     *
     * @param array $pageData The page data of the current page.
     */
    public function __construct(array $pageData)
    {
        $this->pageData = $pageData;
    }

    /**
     * Executes the command.
     *
     * @return void
     */
    public function defaultAction()
    {
        global $sn, $su, $tx;

        $view = new View('tab');
        $view->action = $sn . '?' . $su;
        $view->tag = $this->pageData['polyglott_tag'];
        $view->submit = ucfirst($tx['action']['save']);
        $view->render();
    }
}
