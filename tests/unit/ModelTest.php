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

require_once './vendor/autoload.php';
require_once '../../cmsimple/functions.php';
require_once './classes/model.php';

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

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
     *
     * @return void
     */
    public function setUp()
    {
        $this->defineConstant('CMSIMPLE_URL', 'http://foo.en/');
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        mkdir(vfsStream::url('test/de'));
        touch(vfsStream::url('test/de/.2lang'));
        $this->model = new Model('en', 'en', vfsStream::url('test/'), vfsStream::url('test/'));
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
        uopz_set_return('XH_secondLanguages', ['de']);
        $expected = array('de');
        $actual = $this->model->otherLanguages();
        $this->assertEquals($expected, $actual);
        uopz_unset_return('XH_secondLanguages');
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

    /**
     * (Re)defines a global constant.
     *
     * @param string $name  A name.
     * @param string $value A value.
     *
     * @return void
     */
    protected function defineConstant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            uopz_redefine($name, $value);
        }
    }
}
