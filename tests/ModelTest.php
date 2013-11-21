<?php

require_once 'vfsStream/vfsStream.php';
require './classes/model.php';

class ModelTest extends PHPUnit_Framework_TestCase
{
    protected $model;

    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        mkdir(vfsStream::url('test/de'));
        $this->model = new Polyglott_Model(
            'en', 'en', vfsStream::url('test'), vfsStream::url('test')
        );
    }

    public function testOtherLanguages()
    {
        $expected = array('de');
        $actual = $this->model->otherLanguages();
        $this->assertEquals($expected, $actual);
    }
}

?>
