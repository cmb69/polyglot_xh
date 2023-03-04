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
use Polyglot\Infra\Model;
use Polyglot\Infra\Pages;
use Polyglot\Infra\Request;

class MainAdminController
{
    /**
     * @var Pages
     */
    private $pages;

    /**
     *  @var Model
     */
    private $model;

    /** @var View */
    private $view;

    public function __construct(Pages $pages, Model $model, View $view)
    {
        $this->pages = $pages;
        $this->model = $model;
        $this->view = $view;
    }

    public function defaultAction(Request $request): string
    {
        $otherLanguages = array_filter($this->model->languages(), function (string $language) use ($request) {
            return $language !== $request->sl();
        });
        $languages = $otherLanguages;
        $pages = [];
        for ($i = 0; $i < $this->pages->count(); $i++) {
            $heading = $this->pages->heading($i);
            $url = $request->url()->page($this->pages->url($i))->with("edit");
            $indent = (string) ($this->pages->level($i) - 1);
            $tag = $this->model->pageTag($i);
            $translations = [];
            foreach ($languages as $language) {
                $translations[$language]
                    = $this->model->isTranslated($request->sl(), $tag, $language)
                        ? $this->model->languageURL($request->url(), $request->sl(), $language, $tag)->with("edit")
                        : null;
            }
            $pages[] = compact('heading', 'url', 'indent', 'tag', 'translations');
        }
        return $this->view->render('admin', [
            'languages' => $languages,
            'pages' => $pages,
        ]);
    }
}
