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
     * @param string[] $pageUrls
     */
    public function __construct(
        string $language,
        string $defaultLang,
        string $dataFolder,
        PageDataRouter $pageDataRouter,
        array $pageUrls,
        string $contentFile
    ) {
        $this->language = (string) $language;
        $this->defaultLanguage = (string) $defaultLang;
        $this->dataFolder = (string) $dataFolder;
        $this->pageDataRouter = $pageDataRouter;
        $this->pageUrls = $pageUrls;
        $this->contentFile = (string) $contentFile;
    }

    public function tagsFile(): string
    {
        return $this->dataFolder . 'tags.dat';
    }

    /**
     * @return string[]
     */
    public function languages(): array
    {
        $languages = XH_secondLanguages();
        $languages[] = $this->defaultLanguage;
        sort($languages);
        return $languages;
    }

    /**
     * @return string[]
     */
    public function otherLanguages(): array
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

    private function lastMod(): int
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

    private function isCacheStale(): bool
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
     * @return ?string
     */
    public function pageTag(int $index)
    {
        $pageData = $this->pageDataRouter->find_page($index);
        return isset($pageData['polyglot_tag'])
            ? $pageData['polyglot_tag']
            : null;
    }

    public function languageURL(string $language, string $tag): string
    {
        $res = $this->getInstallationUrl();
        if ($language != $this->defaultLanguage) {
            $res .= $language . '/';
        }
        if ($this->tags === null) {
            $this->init();
        }
        assert(is_array($this->tags));
        if (isset($this->tags[$tag][$language])) {
            $res .= '?' . $this->tags[$tag][$language];
        }
        return $res;
    }

    private function getInstallationUrl(): string
    {
        return (string) preg_replace(
            array('/index\.php$/', '/(?<=\/)' . $this->language . '\/$/'),
            '',
            CMSIMPLE_URL
        );
    }

    public function isTranslated(string $tag, string $language): bool
    {
        if ($this->tags === null) {
            $this->init();
        }
        assert(is_array($this->tags));
        return isset($this->tags[$tag][$language]);
    }
}
