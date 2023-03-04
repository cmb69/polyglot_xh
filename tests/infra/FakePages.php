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

class FakePages extends Pages
{
    public function __construct()
    {
        $this->xhPages = new FakeXhPages;
        $this->xhPageDataRouter = new FakeXhPageDataRouter;
    }
}

class FakeXhPages
{
    public function getCount()
    {
        return 2;
    }

    public function level(int $page)
    {
        $levels = [1, 2];
        return $levels[$page];
    }

    public function heading(int $page)
    {
        $heading = ["Foo", "Bar"];
        return $heading[$page];
    }

    public function url(int $page)
    {
        $urls = ["foo", "bar"];
        return $urls[$page];
    }
}

class FakeXhPageDataRouter
{
    public function find_all()
    {
        return [];
    }
}
