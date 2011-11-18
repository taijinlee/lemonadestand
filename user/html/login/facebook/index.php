<?php
namespace user;

include_once $_SERVER['NP_ROOT'] . '/user/init.php';
$GLOBALS['login_manager']->login_not_required();

class page extends classes\view {

  public function run() {
    $facebook = \lib\facebook::get_facebook_interface();
    if (!($fb_user_info = $facebook->get_user_info())) {
      // something borked about logging in. Redirect to home
      \lib\redirect::home();
    }

    if (!($user = \lib\entity\user::retrieve($fb_user_info['email'], 'email'))) {
      $user_info['first_name'] = $fb_user_info['first_name'];
      $user_info['last_name'] = $fb_user_info['last_name'];
      $user_info['user_name'] = $fb_user_info['name'];
      $user_info['email'] = $fb_user_info['email'];
      $user_info['auth_type'] = 'facebook';
      $user_info['auth_id'] = $fb_user_info['id'];

      $user = \lib\entity\user::create($user_info);
    }

    $GLOBALS['login_manager']->do_facebook_login($user->id);
    $this->render('facebook/logged_in.twig');
  }

}

$page = new page();
$page->run();
