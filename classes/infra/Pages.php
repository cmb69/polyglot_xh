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

namespace Polyglot\Infra;

use XH\PageDataRouter as XhPageDataRouter;
use XH\Pages as XhPages;

class Pages
{
    /** @var XhPages */
    protected $xhPages;

    /** @var XhPageDataRouter */
    protected $xhPageDataRouter;

    public function __construct()
    {
        global $pd_router;

        $this->xhPages = new XhPages;
        $this->xhPageDataRouter = $pd_router;
    }

    public function count(): int
    {
        return $this->xhPages->getCount();
    }

    public function level(int $page): int
    {
        return $this->xhPages->level($page);
    }

    public function heading(int $page): string
    {
        return $this->xhPages->heading($page);
    }

    public function url(int $page): string
    {
        return $this->xhPages->url($page);
    }

    /** @return array<int,array<string,string>> */
    public function allPageData(): array
    {
        return $this->xhPageDataRouter->find_all();
    }

    /** @return array<string,string> */
    public function pageData(int $page): array
    {
        return $this->xhPageDataRouter->find_page($page) ?? []; // @phpstan-ignore-line
    }
}
