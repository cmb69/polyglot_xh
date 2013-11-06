<?php

require './classes/model.php';

class ModelTest extends PHPUnit_Framework_TestCase
{
    protected $model;

    public function setUp()
    {
        $this->model = new Polyglott_Model('en', 'en', './tests/data/', './tests/data/');
    }

    public function testOtherLanguages()
    {
        $expected = array('de');
        $actual = $this->model->otherLanguages();
        $this->assertEquals($expected, $actual);
    }
}

?>
