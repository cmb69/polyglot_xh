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

class ContentReader
{
    /** @var string */
    private $defaultLanguage;

    public function __construct(string $defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * @return array<string,string>|null
     */
    public function readLanguage(string $language)
    {
        $content = XH_readContents($language === $this->defaultLanguage ? $language : null);
        if ($content === false) {
            return null;
        }
        $pageUrls = [];
        $urls = $content["urls"];
        $pd_router = $content["pd_router"];
        $removed = $content["removed"];
        foreach ($pd_router->find_all() as $i => $pageData) {
            if (!$removed[$i] && $pageData["polyglot_tag"] !== "") {
                $tag = (string) $pageData["polyglot_tag"];
                $pageUrls[$tag] = (string) $urls[$i];
            }
        }
        return $pageUrls;
    }
}
