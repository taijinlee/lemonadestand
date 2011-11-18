<?php
namespace user;

include_once $_SERVER['NP_ROOT'] . '/lib/init-global.php';

// specific tier stuff below here
$GLOBALS['login_manager'] =  new classes\login_manager();

\lib\session::init();
if (!session_id()) {
  session_start();
}
