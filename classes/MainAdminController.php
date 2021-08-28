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

class MainAdminController extends Controller
{
    /**
     *  @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        global $sn, $cl, $h, $l, $u;

        $languages = $this->model->otherLanguages();
        $pages = array();
        for ($i = 0; $i < $cl; $i++) {
            $heading = $h[$i];
            $url = $sn . '?' . $u[$i] . '&amp;edit';
            $indent = $l[$i] - 1;
            $tag = $this->pageTag($i);
            $translations = array();
            foreach ($languages as $language) {
                $translations[$language]
                    = $this->model->isTranslated($tag, $language)
                        ? $this->model->languageURL($language, $tag) . '&amp;edit'
                        : null;
            }
            $pages[] = compact('heading', 'url', 'indent', 'tag', 'translations');
        }
        $view = new View('admin');
        $view->render([
            'languages' => $languages,
            'pages' => $pages,
        ]);
    }
}
