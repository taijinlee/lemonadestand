<?php
namespace build;

class databases extends build {

  public static function get_databases($is_prod) {
    $databases = array();

    if ($is_prod) {
      $databases['lemonade'] = array('host' => 'localhost', 'user' => 'lemonade', 'password' => 'lemonade');
      $databases['lemonade_sessions'] = array('host' => 'localhost', 'user' => 'lemonade', 'password' => 'lemonade');
      $databases['lemonade_logs'] = array('host' => 'localhost', 'user' => 'lemonade', 'password' => 'lemonade');
      $databases['unit_test'] = array('host' => 'localhost', 'user' => 'lemonade', 'password' => 'lemonade');
    } else {
      $databases['lemonade'] = array('host' => 'localhost', 'user' => 'lemonade', 'password' => 'lemonade');
      $databases['lemonade_sessions'] = array('host' => 'localhost', 'user' => 'lemonade', 'password' => 'lemonade');
      $databases['lemonade_logs'] = array('host' => 'localhost', 'user' => 'lemonade', 'password' => 'lemonade');
      $databases['unit_test'] = array('host' => 'localhost', 'user' => 'lemonade', 'password' => 'lemonade');
    }
    return $databases;
  }

  protected static function _build($is_prod) {
    foreach (self::get_databases($is_prod) as $key => $value) {
      self::add_param($key, $value);
    }
  }

}
