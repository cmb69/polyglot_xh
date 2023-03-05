<?php

/**
 * Copyright 2023 Christoph M. Becker
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

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Polyglot\Value\Translation;

class TranslationRepoTest extends TestCase
{
    public function testFindsByTagFromContent(): void
    {
        vfsStream::setup("root");
        $root = vfsStream::url("root/");
        mkdir($root . "plugins/polyglot/cache/", 0777, true);
        $cacheFile = $root . "plugins/polyglot/cache/translations.dat";
        $sut = new TranslationRepo(
            $cacheFile,
            $root . "content/",
            "en",
            new FakePages,
            new FakeLanguageRepo(["second" => ["de"]]),
            new FakeContentReader([
                "de" => ["some_tag" => "Eine-Seite"],
                "en" => ["some_tag" => "A-Page"],
            ])
        );
        $this->assertFileDoesNotExist($cacheFile);
        $translation = $sut->findByTag("some_tag");
        $this->assertEquals($this->translation(), $translation);
        $this->assertFileExists($cacheFile);
    }

    public function testFindsByTagFromCache(): void
    {
        vfsStream::setup("root");
        $root = vfsStream::url("root/");
        mkdir($root . "content/de/", 0777, true);
        touch($root . "content/content.htm", 1678031567);
        touch($root . "content/de/content.htm", 1678031567);
        mkdir($root . "plugins/polyglot/cache/", 0777, true);
        $cacheFile = $root . "plugins/polyglot/cache/translations.dat";
        file_put_contents($cacheFile, serialize(["some_tag" => $this->translation()]));
        $sut = new TranslationRepo(
            $cacheFile,
            $root . "content/",
            "en",
            new FakePages,
            new FakeLanguageRepo(),
            new FakeContentReader()
        );
        $this->assertFileExists($cacheFile);
        $translation = $sut->findByTag("some_tag");
        $this->assertEquals($this->translation(), $translation);
    }

    private function translation(): Translation
    {
        return new Translation("some_tag", ["de" => "Eine-Seite", "en" => "A-Page"]);
    }
}
