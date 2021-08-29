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
        foreach ($this->model->languages() as $language) {
            if ($this->model->isTranslated($tag, $language)) {
                $links = array_merge($links, $this->alternateLinksFor($language, $tag));
            }
        }
        ob_start();
        $this->view->render('alternate_links', compact('links'));
        $hjs .= (string) ob_get_clean();
    }

    /**
     * @param string $language
     * @param string $tag
     * @return array<int,array{hreflang:string,href:string}>
     */
    private function alternateLinksFor($language, $tag)
    {
        global $cf;

        $result = [];
        $href = $this->model->languageURL($language, $tag);
        if ($language == $cf['language']['default']) {
            $result[] = $this->renderAlternateLink('x-default', $href);
        }
        $result[] = $this->renderAlternateLink($language, $href);
        return $result;
    }

    /**
     * @param string $hreflang
     * @param string $href
     * @return array{hreflang:string,href:string}
     */
    private function renderAlternateLink($hreflang, $href)
    {
        return compact('hreflang', 'href');
    }
}
