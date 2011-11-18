<?php
namespace user\classes;

class view extends \lib\view {

  /**
   * Initialize view with user tier specifics
   */
  public function __construct() {
    // check to see if user needs to be logged in
    // to disable this, the page needs to call $GLOBALS['login_manager']->login_not_required() before constructing the class
    $GLOBALS['login_manager']->check_login();

    // calling parent specifying user tier
    parent::__construct('user');
  }

}
