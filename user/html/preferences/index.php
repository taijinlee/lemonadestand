<?php
namespace user;

include_once $_SERVER['NP_ROOT'] . '/user/init.php';
$GLOBALS['login_manager']->login_not_required();

class page extends classes\view {
  
  public function run() {
    print_r($_SERVER);
    $cuisines = array('French', 'Chinese', 'Thai', 'Mexican', 'Vietnamese', 'Breakfast / Brunch', 'Latin American', 'Vegetarian',
                      'Seafood', 'Mediterranean', 'Bars', 'Indian', 'Italian', 'Japanese', 'American', 'Sandwiches', 'Burgers',
                      'Diners', 'Pizza', 'Sushi');

    $this->set('cuisines', $cuisines);
    $this->render('user/preferences.twig');
  }

}

$page = new page();
$page->run();
