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

class LanguageMenuController
{
    /** @var string */
    private $flagsFolder;

    /** @var array<string,string> */
    private $conf;

    /**
     * @var Model
     */
    private $model;

    /** @var View */
    private $view;

    /**
     * @param array<string,string> $conf
     */
    public function __construct(string $flagsFolder, array $conf, Model $model, View $view)
    {
        $this->flagsFolder = $flagsFolder;
        $this->conf = $conf;
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        $languages = array();

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
            . $this->conf['flags_extension'];
    }

    private function getAltAttribute(string $language): string
    {
        global $s;

        $tag = $this->model->pageTag($s);
        $labels = $this->languageLabels();
        if (isset($labels[$language])) {
            if (($tag !== null && $this->model->isTranslated($tag, $language))
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
        $languages = preg_split('/\r\n|\r|\n/', $this->conf['languages_labels']);
        assert(is_array($languages));
        $res = array();
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

    private function languageURL(string $language): string
    {
        global $s;

        $tag = $s > 0 ? $this->model->pageTag($s) : null;
        if ($tag === null) {
            return "";
        }
        return $this->model->languageURL($language, $tag);
    }
}
