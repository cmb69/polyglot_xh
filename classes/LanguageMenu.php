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
use Polyglot\Infra\Repository;
use Polyglot\Infra\Request;
use Polyglot\Infra\Response;
use Polyglot\Logic\Util;

class LanguageMenu
{
    /** @var array<string,string> */
    private $conf;

    /** @var string */
    private $flagsFolder;

    /** @var Repository */
    private $repository;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        string $flagsFolder,
        Repository $repository,
        View $view
    ) {
        $this->conf = $conf;
        $this->flagsFolder = $flagsFolder;
        $this->repository = $repository;
        $this->view = $view;
    }

    public function __invoke(Request $request): Response
    {
        $languages = [];
        foreach ($this->repository->otherLanguages($request->sl()) as $language) {
            $languages[$language] = [
                "href" => $this->languageURL($request, $language)->relative(),
                "src" => $this->languageFlag($language),
                "alt" => $this->getAltAttribute($request, $language),
            ];
        }
        return Response::create($this->view->render('languagemenu', ['languages' => $languages]));
    }

    private function languageFlag(string $language): string
    {
        return $this->flagsFolder . $language . '.'
            . $this->conf["flags_extension"];
    }

    private function getAltAttribute(Request $request, string $language): string
    {
        $translation = $this->repository->findTranslationByPage($request->s());
        $labels = Util::parseLanguageLabels($this->conf["languages_labels"]);
        return $this->label($labels, $language, $translation->pageUrl($language) !== null);
    }

    /** @param array<string,array{translated:string,untranslated:string|null}> $labels */
    private function label(array $labels, string $language, bool $translated): string
    {
        if (!isset($labels[$language])) {
             return $language;
        }
        if (!$translated && $labels[$language]["untranslated"] !== null) {
            return $labels[$language]["untranslated"];
        }
        return $labels[$language]["translated"];
    }

    private function languageURL(Request $request, string $language): Url
    {
        $translation = $this->repository->findTranslationByPage($request->s());
        $tag = $request->s() > 0 ? $translation->tag() : "";
        $pageUrl = $this->repository->findTranslationByTag($tag)->pageUrl($language);
        return $request->url()->lang($language != $this->conf["language_default"] ? $language : "")
            ->page($pageUrl !== null ? $pageUrl : "");
    }
}
