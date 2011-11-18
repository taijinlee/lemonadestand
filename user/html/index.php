<?php
namespace user;

include_once $_SERVER['NP_ROOT'] . '/user/init.php';
$GLOBALS['login_manager']->login_not_required();

class page extends classes\view {
  
  public function run() {
    // print_r($_SERVER);
    print_r('blah');
    \lib\log::debug('hi');
    print_r(error_get_last());
    $this->set('login_url', $GLOBALS['login_manager']->get_facebook_login_url());
    $this->render('user/homepage.twig');
  }

}

$page = new page();
$page->run();
