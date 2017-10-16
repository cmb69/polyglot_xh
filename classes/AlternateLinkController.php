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

class AlternateLinkController extends Controller
{
    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Returns the alternate hreflang links.
     *
     * @return void
     */
    public function defaultAction()
    {
        global $s, $hjs;

        $res = '';
        $tag = $this->pageTag($s);
        foreach ($this->model->languages() as $language) {
            if ($this->model->isTranslated($tag, $language)) {
                $res .= $this->alternateLinksFor($language, $tag);
            }
        }
        $hjs .= $res;
    }

    /**
     * Returns the alternate hreflang links for a single language.
     *
     * @param string $language An ISO 639-1 language code.
     * @param string $tag      A polyglott tag.
     *
     * @return string (X)HTML.
     */
    private function alternateLinksFor($language, $tag)
    {
        global $cf;

        $html = '';
        $href = $this->model->languageURL($language, $tag);
        if ($language == $cf['language']['default']) {
            $html .= $this->renderAlternateLink('x-default', $href) . PHP_EOL;
        }
        $html .= $this->renderAlternateLink($language, $href) . PHP_EOL;
        return $html;
    }

    /**
     * Renders an alternate hreflang link.
     *
     * @param string $hreflang A hreflang value.
     * @param string $href     A href value.
     *
     * @return string (X)HTML.
     */
    private function renderAlternateLink($hreflang, $href)
    {
        return tag(
            'link rel="alternate" hreflang="' . XH_hsc($hreflang)
            . '" href="' . XH_hsc($href) . '"'
        );
    }
}
