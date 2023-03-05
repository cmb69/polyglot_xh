<?php

/**
 * Copyright 2012-2023 Christoph M. Becker
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

use Polyglot\Value\Translation;

class TranslationRepo
{
    /** @var string */
    private $cacheFile;

    /** @var string */
    private $generalContentFolder;

    /** @var string */
    private $defaultLanguage;

    /** @var Pages */
    protected $pages;

    /** @var LanguageRepo */
    private $languageRepo;

    /** @var ContentReader */
    private $contentReader;

    /** @var array<string,Translation>|null */
    protected $translations = null;

    public function __construct(
        string $cacheFile,
        string $generalContentFolder,
        string $defaultLanguage,
        Pages $pages,
        LanguageRepo $languageRepo,
        ContentReader $contentReader
    ) {
        $this->cacheFile = $cacheFile;
        $this->generalContentFolder = $generalContentFolder;
        $this->defaultLanguage = $defaultLanguage;
        $this->pages = $pages;
        $this->languageRepo = $languageRepo;
        $this->contentReader = $contentReader;
    }

    public function findByTag(string $tag): Translation
    {
        if ($this->translations === null) {
            $this->init();
        }
        return $this->translations[$tag] ?? new Translation($tag, []);
    }

    public function findByPage(int $page): Translation
    {
        if ($this->translations === null) {
            $this->init();
        }
        $pageData = $this->pages->pageData($page);
        $tag = $pageData['polyglot_tag'] ?? "";
        return $this->findByTag($tag);
    }

    /** @return void */
    protected function init()
    {
        if (!$this->isCacheStale()) {
            $translations = XH_readFile($this->cacheFile);
            if ($translations !== false) {
                $translations = unserialize($translations);
                if (is_array($translations)) {
                    $this->translations = $translations;
                    return;
                }
            }
        }
        $this->doInit();
        $this->updateCache();
    }

    private function isCacheStale(): bool
    {
        if (!file_exists($this->cacheFile)) {
            return true;
        }
        $mTime = 0;
        foreach ($this->languageRepo->all() as $language) {
            $folder = $this->generalContentFolder;
            if ($language !== $this->defaultLanguage) {
                $folder .= $language . "/";
            }
            $file = $folder . "content.htm";
            $mTime = max($mTime, (int) filemtime($file));
        }
        return $mTime > (int) filemtime($this->cacheFile);
    }

    /** @return void */
    private function doInit()
    {
        $tags = [];
        foreach ($this->languageRepo->all() as $language) {
            $pageUrls = $this->contentReader->readLanguage($language);
            if ($pageUrls === null) {
                continue;
            }
            foreach ($pageUrls as $tag => $pageUrl) {
                if (isset($tags[$tag])) {
                    $tags[$tag] += [$language => $pageUrl];
                } else {
                    $tags[$tag] = [$language => $pageUrl];
                }
            }
        }
        foreach ($tags as $tag => $pageUrls) {
            $this->translations[$tag] = new Translation($tag, $pageUrls);
        }
    }

    /** @return void */
    private function updateCache()
    {
        $contents = serialize($this->translations);
        XH_writeFile($this->cacheFile, $contents);
    }
}
