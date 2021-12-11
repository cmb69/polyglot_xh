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
use XH\Pages;

class MainAdminController
{
    /**
     * @var Pages
     */
    private $pages;

    /** @var Url */
    private $url;

    /**
     *  @var Model
     */
    private $model;

    /** @var View */
    private $view;

    public function __construct(Pages $pages, Url $url, Model $model, View $view)
    {
        $this->pages = $pages;
        $this->url = $url;
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        $languages = $this->model->otherLanguages();
        $pages = array();
        for ($i = 0; $i < $this->pages->getCount(); $i++) {
            $heading = $this->pages->heading($i);
            $url = $this->url->page($this->pages->url($i))->with("edit");
            $indent = (string) ($this->pages->level($i) - 1);
            $tag = $this->model->pageTag($i);
            $translations = array();
            foreach ($languages as $language) {
                $translations[$language]
                    = $this->model->isTranslated($tag, $language)
                        ? $this->model->languageURL($language, $tag)->with("edit")
                        : null;
            }
            $pages[] = compact('heading', 'url', 'indent', 'tag', 'translations');
        }
        echo $this->view->render('admin', [
            'languages' => $languages,
            'pages' => $pages,
        ]);
    }
}
