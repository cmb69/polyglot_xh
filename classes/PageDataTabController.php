<?php

/**
 * Copyright 2012-2021 Christoph M. Becker
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

use Plib\HtmlView as View;
use Plib\Url;

class PageDataTabController
{
    /**
     * @var array<string,string>
     */
    private $pageData;

    /** @var Url */
    private $url;

    /** @var View */
    private $view;

    /**
     * @param array<string,string> $pageData
     */
    public function __construct(array $pageData, Url $url, View $view)
    {
        $this->pageData = $pageData;
        $this->url = $url;
        $this->view = $view;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        echo $this->view->render('tab', [
            'action' => $this->url->relative(),
            'tag' => $this->pageData['polyglot_tag'],
        ]);
    }
}
