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

namespace Polyglot\Infra;

use Plib\Url;

class Model
{
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

    /** @var string */
    private $contentFile;

    /** @var Pages */
    private $pages;

    /** @param array<string> $secondLanguages */
    public function __construct(
        string $defaultLang,
        array $secondLanguages,
        string $dataFolder,
        string $contentFile,
        Pages $pages
    ) {
        $this->defaultLanguage = $defaultLang;
        $this->secondLanguages = $secondLanguages;
        $this->dataFolder = $dataFolder;
        $this->contentFile = $contentFile;
        $this->pages = $pages;
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
    private function init(string $sl)
    {
        $filename = $this->tagsFile();
        if (!is_readable($filename)) {
            $this->tags = [];
            $this->update($sl);
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
            $this->update($sl);
            return;
        }
        if ($this->isCacheStale()) {
            $this->update($sl);
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
    private function update(string $sl)
    {
        foreach ($this->pages->allPageData() as $i => $data) {
            if (!empty($data['polyglot_tag'])) {
                $tag = $data['polyglot_tag'];
                $this->tags[$tag][$sl] = $this->pages->url($i);
            }
        }
        $contents = serialize($this->tags);
        XH_writeFile($this->tagsFile(), $contents);
    }

    public function pageTag(int $index): string
    {
        $pageData = $this->pages->pageData($index);
        return isset($pageData['polyglot_tag'])
            ? $pageData['polyglot_tag']
            : "";
    }

    public function languageURL(Url $url, string $sl, string $language, string $tag): Url
    {
        $url = $url->lang("")->page("");
        if ($language != $this->defaultLanguage) {
            $url = $url->lang($language);
        }
        if ($this->tags === null) {
            $this->init($sl);
        }
        assert(is_array($this->tags));
        if (isset($this->tags[$tag][$language])) {
            $url = $url->page($this->tags[$tag][$language]);
        }
        return $url;
    }

    public function isTranslated(string $sl, string $tag, string $language): bool
    {
        if ($this->tags === null) {
            $this->init($sl);
        }
        assert(is_array($this->tags));
        return isset($this->tags[$tag][$language]);
    }
}
