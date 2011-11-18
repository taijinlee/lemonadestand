<?php

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

class viewTest extends PHPUnit_Framework_TestCase {

  public function testConstruct() {
    $view = new viewTestClass();
    $this->assertEquals($view->renderForTest('test/empty.twig'), '');
  }

  public function testSet() {
    $view = new viewTestClass();

    $some_int = 85748;
    $view->setForTest('variable', $some_int);
    $this->assertEquals(trim($view->renderForTest('test/variable.twig')), $some_int);

    $some_string = "%*SN#|ZO!~";
    $view->setForTest('variable', $some_string);
    $this->assertEquals(trim($view->renderForTest('test/variable.twig')), $some_string);
  }


}


class viewTestClass extends \lib\view {

  public function __construct() {
    parent::__construct('test');
  }

  public function renderForTest($template) {
    return $this->render($template, $return = true);
  }

  public function setForTest($key, $value) {
    $this->set($key, $value);

  }

}
