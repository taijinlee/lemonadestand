<?php

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

class sessionTest extends PHPUnit_Framework_TestCase {

  public static function setupBeforeClass() {
    \lib\session::init();
    // ignore warnings here
    @session_start();
  }

  public static function teardownAfterClass() {
    $session_id = session_id();
    session_write_close();
    session_start($session_id);
    session_destroy();
  }

  public function testSession() {
    $_SESSION['blah']['cool'] = 'bar';
    $_SESSION['cool'] = 2;
    $_SESSION[serialize(array(5,7,'wine', 'grape'))] = true;
    $_SESSION['"'] = 5;

    $session_data = array(
      'blah' => array('cool' => 'bar'),
      'cool' => 2,
      'a:4:{i:0;i:5;i:1;i:7;i:2;s:4:"wine";i:3;s:5:"grape";}' => true,
      '"' => 5,
    );

    $this->assertEquals($session_data, $_SESSION);
    $this->assertEquals($_SESSION['blah']['cool'], 'bar');
    $this->assertEquals($_SESSION['cool'], 2);
    $this->assertEquals($_SESSION['a:4:{i:0;i:5;i:1;i:7;i:2;s:4:"wine";i:3;s:5:"grape";}'], true);
    $this->assertEquals($_SESSION['"'], 5);
  }

}
