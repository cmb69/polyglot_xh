<?php

/**
 * Testing the model.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Polyglott
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */

require_once './vendor/autoload.php';
require_once './classes/model.php';

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

/**
 * Testing the model.
 *
 * @category Testing
 * @package  Polyglott
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Polyglott_XH
 */
class ModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * The test subject.
     *
     * @var Polyglott_Model
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
        $this->model = new Polyglott_Model(
            'en', 'en', vfsStream::url('test/'), vfsStream::url('test/')
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
        $this->assertEquals(
            'http://foo.en/de/', $this->model->languageURL('de', 'unknown')
        );
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
            runkit_constant_redefine($name, $value);
        }
    }
}

?>
