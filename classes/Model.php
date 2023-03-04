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

use Plib\Url;
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

    /** @var array<string> */
    private $secondLanguages;

    /**
     * @var string
     */
    private $dataFolder;

    /**
     * @var array<string,array<string,string>>|null
     */
    private $tags = null;

    /** @var PageDataRouter */
    private $pageDataRouter;

    /** @var string[] */
    private $pageUrls;

    /** @var string */
    private $contentFile;

    /** @var Url */
    private $url;

    /**
     * @param array<string> $secondLanguages
     * @param string[] $pageUrls
     */
    public function __construct(
        string $language,
        string $defaultLang,
        array $secondLanguages,
        string $dataFolder,
        PageDataRouter $pageDataRouter,
        array $pageUrls,
        string $contentFile,
        Url $url
    ) {
        $this->language = $language;
        $this->defaultLanguage = $defaultLang;
        $this->secondLanguages = $secondLanguages;
        $this->dataFolder = $dataFolder;
        $this->pageDataRouter = $pageDataRouter;
        $this->pageUrls = $pageUrls;
        $this->contentFile = $contentFile;
        $this->url = $url;
    }

    private function tagsFile(): string
    {
        return $this->dataFolder . 'tags.dat';
    }

    /**
     * @return string[]
     */
    public function languages(): array
    {
        $languages = $this->secondLanguages;
        $languages[] = $this->defaultLanguage;
        sort($languages);
        return $languages;
    }

    /**
     * @return string[]
     */
    public function otherLanguages(): array
    {
        $res = [];
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
        $tags = unserialize($contents);
        assert(is_array($tags));
        $this->tags = $tags;
        if (!is_array($this->tags)) { // @phpstan-ignore-line
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
                $this->tags[$tag][$this->language] = $this->pageUrls[$i]; // @phpstan-ignore-line
            }
        }
        $contents = serialize($this->tags);
        XH_writeFile($this->tagsFile(), $contents);
    }

    public function pageTag(int $index): string
    {
        $pageData = $this->pageDataRouter->find_page($index);
        return isset($pageData['polyglot_tag'])
            ? $pageData['polyglot_tag']
            : "";
    }

    public function languageURL(string $language, string $tag): Url
    {
        $url = $this->url->lang("")->page("");
        if ($language != $this->defaultLanguage) {
            $url = $url->lang($language);
        }
        if ($this->tags === null) {
            $this->init();
        }
        assert(is_array($this->tags));
        if (isset($this->tags[$tag][$language])) {
            $url = $url->page($this->tags[$tag][$language]);
        }
        return $url;
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
