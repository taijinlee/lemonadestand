<?php

include_once $_SERVER['NP_ROOT'] . '/lib/autoload.php';

class autoloadTest extends PHPUnit_Framework_TestCase {

  public function testAutoLoad() {
    \lib\autoload::register();
    $this->assertEquals(\test\lib\autoload_test_class::test(), true);
    // if it gets here we are good
    $this->assertEquals(1,1);
  }

}

