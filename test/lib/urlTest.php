<?php

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

class urlTest extends PHPUnit_Framework_TestCase {

  public function testUrl() {
    $url = 'http://www.google.com/some_random_stuff';
    $this->assertEquals('http://www.google.com/some_random_stuff?blah=some_value', $url = \lib\url::add_parameter($url, 'blah', 'some_value'));
    $this->assertEquals('http://www.google.com/some_random_stuff?blah=some_value&blah1=some_value2', $url = \lib\url::add_parameter($url, 'blah1', 'some_value2'));
    $this->assertEquals('http://www.google.com/some_random_stuff?blah=some_value&blah1=some_value2&blah3=some_value4', $url = \lib\url::add_parameter($url, 'blah3', 'some_value4'));
    $this->assertEquals('http://www.google.com/some_random_stuff?blah=some_value4&blah1=some_value2&blah3=some_value4', $url = \lib\url::add_parameter($url, 'blah', 'some_value4'));
    $this->assertEquals('http://www.google.com/some_random_stuff?blah=some_value4&blah3=some_value4', $url = \lib\url::remove_parameter($url, 'blah1'));
    $this->assertEquals('http://www.google.com/some_random_stuff?blah=some_value4&blah3=some_value4', $url = \lib\url::remove_parameter($url, 'blah1'));
    $this->assertEquals('http://www.google.com/some_random_stuff?blah=some_value4&blah3=some_value4', $url = \lib\url::remove_parameter($url, 'blah10'));

  }




}
