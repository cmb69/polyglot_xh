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

use XH\PageDataRouter;

class Model
{
    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $defaultLanguage;

    /**
     * @var string
     */
    private $dataFolder;

    /**
     * @var array<string,array>|null
     */
    private $tags = null;

    /** @var PageDataRouter */
    private $pageDataRouter;

    /** @var string[] */
    private $pageUrls;

    /** @var string */
    private $contentFile;

    /**
     * @param string $language
     * @param string $defaultLang
     * @param string $dataFolder
     * @param string[] $pageUrls
     * @param string $contentFile
     */
    public function __construct(
        $language,
        $defaultLang,
        $dataFolder,
        PageDataRouter $pageDataRouter,
        array $pageUrls,
        $contentFile
    ) {
        $this->language = (string) $language;
        $this->defaultLanguage = (string) $defaultLang;
        $this->dataFolder = (string) $dataFolder;
        $this->pageDataRouter = $pageDataRouter;
        $this->pageUrls = $pageUrls;
        $this->contentFile = (string) $contentFile;
    }

    /**
     * @return string
     */
    public function tagsFile()
    {
        return $this->dataFolder . 'tags.dat';
    }

    /**
     * @return string[]
     */
    public function languages()
    {
        $languages = XH_secondLanguages();
        $languages[] = $this->defaultLanguage;
        sort($languages);
        return $languages;
    }

    /**
     * @return string[]
     */
    public function otherLanguages()
    {
        $res = array();
        $languages = $this->languages();
        foreach ($languages as $language) {
            if ($language != $this->language) {
                $res[] = $language;
            }
        }
        return $res;
    }

    /**
     * @return int
     */
    private function lastMod()
    {
        $filename = $this->tagsFile();
        return file_exists($filename)
            ? (int) filemtime($filename)
            : 0;
    }

    /**
     * @return void
     */
    private function init()
    {
        $filename = $this->tagsFile();
        if (!is_readable($filename)) {
            $this->tags = [];
            $this->update();
            return;
        }
        if (!($contents = XH_readFile($filename))) {
            $contents = serialize([]);
        }
        $this->tags = unserialize($contents);
        if (!is_array($this->tags)) {
            $this->tags = [];
            $this->update();
            return;
        }
        if ($this->isCacheStale()) {
            $this->update();
        }
    }

    /**
     * @return bool
     */
    private function isCacheStale()
    {
        $contentLastMod = filemtime($this->contentFile);
        $tagsLastMod = $this->lastMod();
        return $tagsLastMod < $contentLastMod;
    }

    /**
     * @return void
     */
    private function update()
    {
        foreach ($this->pageDataRouter->find_all() as $i => $data) {
            if (!empty($data['polyglot_tag'])) {
                $tag = $data['polyglot_tag'];
                $this->tags[$tag][$this->language] = $this->pageUrls[$i];
            }
        }
        $contents = serialize($this->tags);
        XH_writeFile($this->tagsFile(), $contents);
    }

    /**
     * @param string $language
     * @param string $tag
     * @return string
     */
    public function languageURL($language, $tag)
    {
        $res = $this->getInstallationUrl();
        if ($language != $this->defaultLanguage) {
            $res .= $language . '/';
        }
        if ($this->tags === null) {
            $this->init();
        }
        if (isset($this->tags[$tag][$language])) {
            $res .= '?' . $this->tags[$tag][$language];
        }
        return $res;
    }

    /**
     * @return string
     */
    private function getInstallationUrl()
    {
        return preg_replace(
            array('/index\.php$/', '/(?<=\/)' . $this->language . '\/$/'),
            '',
            CMSIMPLE_URL
        );
    }

    /**
     * @param string $tag
     * @param string $language
     * @return bool
     */
    public function isTranslated($tag, $language)
    {
        if ($this->tags === null) {
            $this->init();
        }
        return isset($this->tags[$tag][$language]);
    }
}
