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
use Polyglot\Infra\Model;
use Polyglot\Infra\Request;
use Polyglot\Infra\TranslationRepo;

class LanguageMenuController
{
    /** @var string */
    private $defaultLanguage;

    /** @var string */
    private $flagsFolder;

    /** @var string */
    private $flagsExtension;

    /** @var string */
    private $languageLabels;

    /** @var View */
    private $view;

    /** @var LanguageRepo */
    private $languageRepo;

    /** @var TranslationRepo */
    private $translationRepo;

    public function __construct(
        string $defaultLanguage,
        string $flagsFolder,
        string $flagsExtension,
        string $languageLabels,
        View $view,
        LanguageRepo $languageRepo,
        TranslationRepo $translationRepo
    ) {
        $this->defaultLanguage = $defaultLanguage;
        $this->flagsFolder = $flagsFolder;
        $this->flagsExtension = $flagsExtension;
        $this->languageLabels = $languageLabels;
        $this->view = $view;
        $this->languageRepo = $languageRepo;
        $this->translationRepo = $translationRepo;
    }

    public function defaultAction(Request $request): string
    {
        $this->translationRepo->init($request->sl());
        $languages = [];
        foreach ($this->languageRepo->others($request->sl()) as $language) {
            $href = $this->languageURL($request, $language);
            $src = $this->languageFlag($language);
            $alt = $this->getAltAttribute($request, $language);
            $languages[$language] = compact('href', 'src', 'alt');
        }
        return $this->view->render('languagemenu', ['languages' => $languages]);
    }

    private function languageFlag(string $language): string
    {
        return $this->flagsFolder . $language . '.'
            . $this->flagsExtension;
    }

    private function getAltAttribute(Request $request, string $language): string
    {
        $translation = $this->translationRepo->findByPage($request->s());
        $labels = $this->languageLabels();
        if (isset($labels[$language])) {
            if ($translation->pageUrl($language) !== null
                || !isset($labels[$language]["untranslated"])
            ) {
                $alt = $labels[$language]["translated"];
            } else {
                $alt = $labels[$language]["untranslated"];
            }
        } else {
            $alt = $language;
        }
        return $alt;
    }

    /**
     * @return array<string,array{translated:string,untranslated?:string}>
     */
    private function languageLabels(): array
    {
        $languages = preg_split('/\r\n|\r|\n/', $this->languageLabels);
        assert(is_array($languages));
        $res = [];
        foreach ($languages as $language) {
            list($key, $value) = explode('=', $language, 2);
            $parts = explode(';', $value, 2);
            $res[$key]["translated"] = $parts[0];
            if (isset($parts[1])) {
                $res[$key]["untranslated"] = $parts[1];
            }
        }
        return $res;
    }

    private function languageURL(Request $request, string $language): Url
    {
        $translation = $this->translationRepo->findByPage($request->s());
        $tag = $request->s() > 0 ? $translation->tag() : "";
        $pageUrl = $this->translationRepo->findByTag($tag)->pageUrl($language);
        return $request->url()->lang($language != $this->defaultLanguage ? $language : "")
            ->page($pageUrl !== null ? $pageUrl : "");
    }
}
