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

use Polyglot\Infra\Repository;
use Polyglot\Value\Translation;

class FakeRepository extends Repository
{
    private $options;

    public function __construct($options = [])
    {
        $this->languages = new FakeLanguages($options["lang"] ?? []);
        $this->pages = new FakePages;
        $this->options = $options;
    }

    public function findTranslationByTag(string $tag): Translation
    {
        return $this->options["trans"][$tag] ?? parent::findTranslationByTag($tag);
    }

    public function findTranslationByPage(int $page): Translation
    {
        return $this->options["trans"][$page] ?? parent::findTranslationByPage($page);
    }

    protected function init()
    {
        $this->translations = [];
    }
}
