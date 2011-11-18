<?php

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

class StackTest extends PHPUnit_Framework_TestCase
{
  public function testPushAndPop()
  {
    \lib\entity\user::create();
    $stack = array();
    $this->assertEquals(0, count($stack));
 
    array_push($stack, 'foo');
    $this->assertEquals('foo', $stack[count($stack)-1]);
    $this->assertEquals(1, count($stack));
 
    $this->assertEquals('foo', array_pop($stack));
    $this->assertEquals(0, count($stack));
  }
}
