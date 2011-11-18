<?php

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

class tokenTest extends PHPUnit_Framework_TestCase {

  public function testToken() {
    $time = time();
    $tokenize = '73#4$jsu%x9&2k(1h@xoq_;1"jdo2';
    $salt = '73x-1{}|-8d0c /.,>{<P';
    $token = \lib\token::generate($tokenize, $salt, $time, 2);
    $this->assertEquals(\lib\token::match($token, $tokenize, $salt, $time, 2), true);
    sleep(3);
    $this->assertEquals(\lib\token::match($token, $tokenize, $salt, $time, 2), false);
  }

}
