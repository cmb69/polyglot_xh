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

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;
use XH\PageDataRouter;

define('CMSIMPLE_URL', 'http://foo.en/');

function XH_secondLanguages()
{
    return ["de"];
}

class ModelTest extends TestCase
{
    /**
     * @var Model
     */
    protected $model;

    public function setUp(): void
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        mkdir(vfsStream::url('test/de'));
        touch(vfsStream::url('test/de/.2lang'));
        $pageDataRouter = $this->createStub(PageDataRouter::class);
        $pageDataRouter->method('find_all')->willReturn([]);
        $this->model = new Model(
            'en',
            'en',
            vfsStream::url('test/'),
            $pageDataRouter,
            [],
            vfsStream::url("test/content/content.htm")
        );
    }

    /**
     * @return void
     */
    public function testTagsFile()
    {
        $expected = vfsStream::url('test/tags.dat');
        $actual = $this->model->tagsFile();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testOtherLanguages()
    {
        $expected = array('de');
        $actual = $this->model->otherLanguages();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     * @link email://C824A36AF8B46744B2AC8F8F671C5ED945D6468116@VServer-02
     */
    public function testLanguageUrlContainsTld()
    {
        $this->assertEquals('http://foo.en/de/', $this->model->languageURL('de', 'unknown'));
    }
}
