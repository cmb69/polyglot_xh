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
use Polyglot\Infra\LanguageRepo;
use Polyglot\Infra\Pages;
use Polyglot\Infra\Request;
use Polyglot\Infra\TranslationRepo;

class MainAdminController
{
    /** @var array<string,string> */
    private $conf;

    /** @var Pages */
    private $pages;

    /** @var View */
    private $view;

    /** @var LanguageRepo */
    private $languageRepo;

    /** @var TranslationRepo */
    private $translationRepo;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        Pages $pages,
        View $view,
        LanguageRepo $languageRepo,
        TranslationRepo $translationRepo
    ) {
        $this->conf = $conf;
        $this->pages = $pages;
        $this->view = $view;
        $this->languageRepo = $languageRepo;
        $this->translationRepo = $translationRepo;
    }

    public function defaultAction(Request $request): string
    {
        $this->translationRepo->init($request->sl());
        $languages = $this->languageRepo->others($request->sl());
        $pages = [];
        for ($i = 0; $i < $this->pages->count(); $i++) {
            $heading = $this->pages->heading($i);
            $url = $request->url()->page($this->pages->url($i))->with("edit");
            $indent = (string) ($this->pages->level($i) - 1);
            $translation = $this->translationRepo->findByPage($i);
            $tag = $translation->tag();
            $translations = [];
            foreach ($languages as $language) {
                $translations[$language] = $translation->pageUrl($language) !== null
                    ? $request->url()->lang($language != $this->conf["language_default"] ? $language : "")
                        ->page($translation->pageUrl($language))->with("edit")
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
