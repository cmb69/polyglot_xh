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

namespace Polyglot;

class PageDataTabController extends Controller
{
    /**
     * @var array
     */
    private $pageData;

    /**
     * @param array $pageData
     */
    public function __construct(array $pageData)
    {
        $this->pageData = $pageData;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        global $sn, $su, $tx;

        $view = new View('tab');
        $view->action = $sn . '?' . $su;
        $view->tag = $this->pageData['polyglot_tag'];
        $view->submit = ucfirst($tx['action']['save']);
        $view->render();
    }
}
