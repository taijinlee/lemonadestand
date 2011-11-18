<?php
namespace lib\cache;

/**
 * Static request caching
 * Lasts for the lifetime of the request
 */

class static_request {

  protected $static_cache;

  public static function set($key, $value) {
    self::$static_cache[$key] = $value;
  }

  public static function get($key) {
    if (!isset(self::$static_cache[$key])) {
      return false;
    }
    return self::$static_cache[$key];
  }

}
