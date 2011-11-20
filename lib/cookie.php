<?php
namespace lib;

/**
 * Basic cookie functionality
 */
class cookie {

  public static function set($name, $value, $expire = 0, $path = '/', $domain = false, $secure = false, $http_only = false) {
    if (!$domain) {
      $domain = \lib\conf\constants::$domain;
    }
    setcookie($name, $value, $expire, $path, $domain, $secure, $http_only);
  }

  public static function get($key) {
    if (isset($_COOKIE[$key])) {
      return $_COOKIE[$key];
    }
    return false;
  }

}
