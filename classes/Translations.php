<?php

/**
 * Copyright 2012-2023 Christoph M. Becker
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
use Polyglot\Infra\Pages;
use Polyglot\Infra\Repository;
use Polyglot\Infra\Request;
use Polyglot\Infra\Response;
use Polyglot\Value\Translation;

class Translations
{
    /** @var array<string,string> */
    private $conf;

    /** @var Pages */
    private $pages;

    /** @var Repository */
    private $repository;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        Pages $pages,
        Repository $repository,
        View $view
    ) {
        $this->conf = $conf;
        $this->pages = $pages;
        $this->repository = $repository;
        $this->view = $view;
    }

    public function __invoke(Request $request): Response
    {
        $languages = $this->repository->otherLanguages($request->sl());
        return Response::create($this->view->render('translations', [
            'languages' => $languages,
            'pages' => $this->pages($request->url(), $languages),
        ]))->withTitle("Polyglot – " . $this->view->text('label_translations'));
    }

    /**
     * @param list<string> $languages
     * @return list<array{heading:string,url:string,indent:string,tag:string,translations:array<string,?string>}>
     */
    private function pages(Url $url, array $languages): array
    {
        $pages = [];
        for ($i = 0; $i < $this->pages->count(); $i++) {
            $translation = $this->repository->findTranslationByPage($i);
            $pages[] = [
                "heading" => $this->pages->heading($i),
                "url" => $url->page($this->pages->url($i))->with("edit")->relative(),
                "indent" => (string) ($this->pages->level($i) - 1),
                "tag" => $translation->tag(),
                "translations" => $this->translations($url, $languages, $translation),
            ];
        }
        return $pages;
    }

    /**
     * @param list<string> $languages
     * @return array<string,?string>
     */
    private function translations(Url $url, array $languages, Translation $translation): array
    {
        $translations = [];
        foreach ($languages as $language) {
            $translations[$language] = $translation->pageUrl($language) !== null
                ? $url->lang($language != $this->conf["language_default"] ? $language : "")
                    ->page($translation->pageUrl($language))->with("edit")->relative()
                : null;
        }
        return $translations;
    }
}
