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
use Polyglot\Infra\Request;

class LanguageMenuController
{
    /** @var string */
    private $flagsFolder;

    /** @var string */
    private $flagsExtension;

    /** @var string */
    private $languageLabels;

    /**
     * @var Model
     */
    private $model;

    /** @var View */
    private $view;

    public function __construct(
        string $flagsFolder,
        string $flagsExtension,
        string $languageLabels,
        Model $model,
        View $view
    ) {
        $this->flagsFolder = $flagsFolder;
        $this->flagsExtension = $flagsExtension;
        $this->languageLabels = $languageLabels;
        $this->model = $model;
        $this->view = $view;
    }

    public function defaultAction(Request $request): string
    {
        $languages = [];

        $otherLanguages = array_filter($this->model->languages(), function (string $language) use ($request) {
            return $language !== $request->sl();
        });
        foreach ($otherLanguages as $language) {
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
        $tag = $this->model->pageTag($request->s());
        $labels = $this->languageLabels();
        if (isset($labels[$language])) {
            if (($this->model->isTranslated($tag, $request->sl(), $language))
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
        $tag = $request->s() > 0 ? $this->model->pageTag($request->s()) : "";
        return $this->model->languageURL($request->url(), $request->sl(), $language, $tag);
    }
}
