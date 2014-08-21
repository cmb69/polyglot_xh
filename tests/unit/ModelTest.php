<?php

require_once './vendor/autoload.php';
require_once './classes/model.php';

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

class ModelTest extends PHPUnit_Framework_TestCase
{
    protected $model;

    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        mkdir(vfsStream::url('test/de'));
        $this->model = new Polyglott_Model(
            'en', 'en', vfsStream::url('test/'), vfsStream::url('test/')
        );
    }

    public function testTagsFile()
    {
        $expected = vfsStream::url('test/tags.dat');
        $actual = $this->model->tagsFile();
        $this->assertEquals($expected, $actual);
    }

    public function testOtherLanguages()
    {
        $expected = array('de');
        $actual = $this->model->otherLanguages();
        $this->assertEquals($expected, $actual);
    }
}

?>
