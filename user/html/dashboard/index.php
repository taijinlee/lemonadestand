<?php
namespace user;

include_once $_SERVER['NP_ROOT'] . '/user/init.php';
$GLOBALS['login_manager']->login_not_required();

class page extends classes\view {
  
  public function run() {
    $this->render('user/dashboard.twig');
  }

}

$page = new page();
$page->run();
