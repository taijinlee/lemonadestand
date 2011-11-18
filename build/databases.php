<?php
namespace build;

class databases extends build {

  public static function get_databases($is_prod) {
    $databases = array();

    if ($is_prod) {
      /* $databases['database']       = array('host' => 'localhost', 'user' => 'nopuku', 'password' => 'nopuku'); */
      /* $databases['sessions']   = array('host' => 'localhost', 'user' => 'nopuku', 'password' => 'nopuku'); */
    } else {
      $databases['rez'] = array('host' => 'localhost', 'user' => 'rez', 'password' => 'stanford11');
      $databases['sessions'] = array('host' => 'localhost', 'user' => 'rez', 'password' => 'stanford11');
      $databases['logs'] = array('host' => 'localhost', 'user' => 'rez', 'password' => 'stanford11');
      $databases['unit_test'] = array('host' => 'localhost', 'user' => 'rez', 'password' => 'stanford11');
    }
    return $databases;
  }

  protected static function _build($is_prod) {
    foreach (self::get_databases($is_prod) as $key => $value) {
      self::add_param($key, $value);
    }
  }

}
