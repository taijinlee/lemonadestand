<?php

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

class attributesTest extends PHPUnit_Framework_TestCase {

  protected $attributes;
  protected $initialize = array('1' => 4, 'cool' => "!i=)_]23'", 4 => 'bleh');

  protected function setUp() {
    $this->attributes = attributesTestClass::create($this->initialize);
  }

  public function testInitializeCorrectly() {
    $this->assertEquals($this->initialize, $this->attributes->get_attributes());
  }

  public function testTouchChange() {
    $this->assertEquals($this->attributes->is_changed(), false);
    $this->attributes->touch();
    $this->assertEquals($this->attributes->is_changed(), true);
  }

  public function testChangeValues() {
    $this->attributes['set'] = 'coolio';
    $this->assertEquals($this->attributes->get_attributes(), $this->initialize + array('set' => 'coolio'));
    unset($this->attributes['set']);
    $this->assertEquals($this->attributes->get_attributes(), $this->initialize);
  }

  public function testExists() {
    $this->attributes['exists'] = 'exists';
    $this->assertEquals(isset($this->attributes['exists']), true);
    $this->assertEquals(isset($this->attributes['does_not_exist']), false);
  }

}


class attributesTestClass extends \lib\attributes {

  protected function __construct($params) {
    parent::__construct($params);
  }

  public static function create($params) {
    return new self($params);
  }
}
