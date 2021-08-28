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

/**
 * Testing the model.
 *
 * @category Testing
 * @package  Polyglot
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglot_XH
 */
class ModelTest extends TestCase
{
    /**
     * The test subject.
     *
     * @var Model
     */
    protected $model;

    /**
     * Sets up the test fixture.
     */
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
     * Tests ::tagsFile().
     *
     * @return void
     */
    public function testTagsFile()
    {
        $expected = vfsStream::url('test/tags.dat');
        $actual = $this->model->tagsFile();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ::otherLanguages().
     *
     * @return void
     */
    public function testOtherLanguages()
    {
        $expected = array('de');
        $actual = $this->model->otherLanguages();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that the language URL contains the TLD, even if this is the same
     * as the language.
     *
     * @link email://C824A36AF8B46744B2AC8F8F671C5ED945D6468116@VServer-02
     *
     * @return void
     */
    public function testLanguageUrlContainsTld()
    {
        $this->assertEquals('http://foo.en/de/', $this->model->languageURL('de', 'unknown'));
    }
}
