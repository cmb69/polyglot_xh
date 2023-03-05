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
use Polyglot\Infra\LanguageRepo;
use Polyglot\Infra\Request;
use Polyglot\Infra\TranslationRepo;
use Polyglot\Logic\Util;

class LanguageMenuController
{
    /** @var array<string,string> */
    private $conf;

    /** @var string */
    private $flagsFolder;

    /** @var View */
    private $view;

    /** @var LanguageRepo */
    private $languageRepo;

    /** @var TranslationRepo */
    private $translationRepo;

    /** @param array<string,string> $conf */
    public function __construct(
        array $conf,
        string $flagsFolder,
        View $view,
        LanguageRepo $languageRepo,
        TranslationRepo $translationRepo
    ) {
        $this->conf = $conf;
        $this->flagsFolder = $flagsFolder;
        $this->view = $view;
        $this->languageRepo = $languageRepo;
        $this->translationRepo = $translationRepo;
    }

    public function defaultAction(Request $request): string
    {
        $this->translationRepo->init($request->sl());
        $languages = [];
        foreach ($this->languageRepo->others($request->sl()) as $language) {
            $languages[$language] = [
                "href" => $this->languageURL($request, $language),
                "src" => $this->languageFlag($language),
                "alt" => $this->getAltAttribute($request, $language),
            ];
        }
        return $this->view->render('languagemenu', ['languages' => $languages]);
    }

    private function languageFlag(string $language): string
    {
        return $this->flagsFolder . $language . '.'
            . $this->conf["flags_extension"];
    }

    private function getAltAttribute(Request $request, string $language): string
    {
        $translation = $this->translationRepo->findByPage($request->s());
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
        $translation = $this->translationRepo->findByPage($request->s());
        $tag = $request->s() > 0 ? $translation->tag() : "";
        $pageUrl = $this->translationRepo->findByTag($tag)->pageUrl($language);
        return $request->url()->lang($language != $this->conf["language_default"] ? $language : "")
            ->page($pageUrl !== null ? $pageUrl : "");
    }
}
