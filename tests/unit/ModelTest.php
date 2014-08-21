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
 * @version   SVN: $Id$
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
}

?>
