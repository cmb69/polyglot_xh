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

class LanguageMenuController
{
    /** @var string */
    private $flagsFolder;

    /** @var string */
    private $flagsExtension;

    /** @var string */
    private $languageLabels;

    /** @var int */
    private $pageIndex;

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
        int $pageIndex,
        Model $model,
        View $view
    ) {
        $this->flagsFolder = $flagsFolder;
        $this->flagsExtension = $flagsExtension;
        $this->languageLabels = $languageLabels;
        $this->pageIndex = $pageIndex;
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        $languages = [];

        foreach ($this->model->otherLanguages() as $language) {
            $href = $this->languageURL($language);
            $src = $this->languageFlag($language);
            $alt = $this->getAltAttribute($language);
            $languages[$language] = compact('href', 'src', 'alt');
        }
        echo $this->view->render('languagemenu', ['languages' => $languages]);
    }

    private function languageFlag(string $language): string
    {
        return $this->flagsFolder . $language . '.'
            . $this->flagsExtension;
    }

    private function getAltAttribute(string $language): string
    {
        $tag = $this->model->pageTag($this->pageIndex);
        $labels = $this->languageLabels();
        if (isset($labels[$language])) {
            if (($this->model->isTranslated($tag, $language))
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

    private function languageURL(string $language): Url
    {
        $tag = $this->pageIndex > 0 ? $this->model->pageTag($this->pageIndex) : "";
        return $this->model->languageURL($language, $tag);
    }
}
