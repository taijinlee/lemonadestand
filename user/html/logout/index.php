<?php
namespace user;

include_once $_SERVER['NP_ROOT'] . '/user/init.php';

class page extends classes\view {

  public function run() {
    $GLOBALS['login_manager']->do_logout();
    \lib\redirect::home();
  }

}

$page = new page();
$page->run();
