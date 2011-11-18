<?php
namespace lib;

/**
 * Basic cookie functionality
 */
class cookie {

  const DEFAULT_COOKIE_DOMAIN = '.rezassured.com';

  public static function set($name, $value, $expire = 0, $path = '/', $domain = self::DEFAULT_COOKIE_DOMAIN, $secure = false, $http_only = false) {
    setcookie($name, $value, $expire, $path, $domain, $secure, $http_only);
  }

  public static function get($key) {
    if (isset($_COOKIE[$key])) {
      return $_COOKIE[$key];
    }
    return false;
  }

}
