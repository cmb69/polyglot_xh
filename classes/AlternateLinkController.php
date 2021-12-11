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

class AlternateLinkController
{
    /**
     * @var Model
     */
    private $model;

    /**
     * @var View
     */
    private $view;

    public function __construct(Model $model, View $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        global $s, $hjs;

        $links = [];
        $tag = $this->model->pageTag($s);
        if ($tag !== null) {
            foreach ($this->model->languages() as $language) {
                if ($this->model->isTranslated($tag, $language)) {
                    $links = array_merge($links, $this->alternateLinksFor($language, $tag));
                }
            }
        }
        $hjs .= $this->view->render('alternate_links', compact('links'));
    }

    /**
     * @return array<int,array{hreflang:string,href:Url}>
     */
    private function alternateLinksFor(string $language, string $tag): array
    {
        global $cf;

        $result = [];
        $href = $this->model->languageURL($language, $tag);
        if ($language == $cf['language']['default']) {
            $result[] = ["hreflang" => "x-default", "href" => $href];
        }
        $result[] = ["hreflang" => $language, "href" => $href];
        return $result;
    }
}
