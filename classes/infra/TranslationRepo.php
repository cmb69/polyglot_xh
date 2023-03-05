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
    private $dataFolder;

    /** @var Pages */
    protected $pages;

    /** @var array<string,array<string,string>>|null */
    protected $tags = null;

    public function __construct(string $dataFolder, Pages $pages)
    {
        $this->dataFolder = $dataFolder;
        $this->pages = $pages;
    }

    /** @return void */
    public function init(string $sl)
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
        if ($this->lastMod() < $this->contentFileTimestamp()) {
            $this->update($sl);
        }
    }

    /** @return void */
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

    protected function contentFileTimestamp(): int
    {
        global $pth;
        return (int) filemtime($pth["file"]["content"]);
    }

    private function lastMod(): int
    {
        $filename = $this->tagsFile();
        return file_exists($filename)
            ? (int) filemtime($filename)
            : 0;
    }

    private function tagsFile(): string
    {
        return $this->dataFolder . 'tags.dat';
    }

    public function findByTag(string $tag): Translation
    {
        assert($this->tags !== null);
        return new Translation($tag, $this->tags[$tag] ?? []);
    }

    public function findByPage(int $page): Translation
    {
        assert($this->tags !== null);
        $pageData = $this->pages->pageData($page);
        $tag = $pageData['polyglot_tag'] ?? "";
        return $this->findByTag($tag);
    }
}
