<?php

/**
 * Copyright 2012-2017 Christoph M. Becker
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

class LanguageMenuController extends Controller
{
    /**
     * The model instance.
     *
     * @var Model
     */
    private $model;

    /**
     * Initializes a new instance.
     */
    public function __construct()
    {
        global $pth, $sl, $cf, $pd_router, $u;

        $this->model = new Model(
            $sl,
            $cf['language']['default'],
            $pth['folder']['plugins'] . 'polyglot/cache/',
            $pd_router,
            $u,
            $pth['file']['content']
        );
        $this->model->init();
    }

    /**
     * @return void
     */
    public function defaultAction()
    {
        $languages = array();

        foreach ($this->model->otherLanguages() as $language) {
            $href = XH_hsc($this->languageURL($language));
            $src = $this->languageFlag($language);
            $alt = XH_hsc($this->getAltAttribute($language));
            $languages[$language] = compact('href', 'src', 'alt');
        }
        $view = new View('languagemenu');
        $view->render(['languages' => $languages]);
    }

    /**
     * @param string $language
     * @return string
     */
    private function languageFlag($language)
    {
        global $pth, $plugin_cf;

        return $pth['folder']['flags'] . $language . '.'
            . $plugin_cf['polyglot']['flags_extension'];
    }

    /**
     * @param string $language
     * @return string
     */
    private function getAltAttribute($language)
    {
        global $s;

        $tag = $this->pageTag($s);
        $labels = $this->languageLabels();
        if (isset($labels[$language])) {
            if ($this->model->isTranslated($tag, $language)
                || !isset($labels[$language][1])
            ) {
                $alt = $labels[$language][0];
            } else {
                $alt = $labels[$language][1];
            }
        } else {
            $alt = $language;
        }
        return $alt;
    }

    /**
     * @return array<string,array>
     */
    private function languageLabels()
    {
        global $plugin_cf;

        $pcf = $plugin_cf['polyglot'];
        $languages = preg_split('/\r\n|\r|\n/', $pcf['languages_labels']);
        $res = array();
        foreach ($languages as $language) {
            list($key, $value) = explode('=', $language);
            $res[$key] = explode(';', $value);
        }
        return $res;
    }

    /**
     * @param string $language
     * @return string
     */
    private function languageURL($language)
    {
        global $s;

        $tag = $s > 0 ? $this->pageTag($s) : null;
        $res = $this->model->languageURL($language, $tag);
        return $res;
    }
}
