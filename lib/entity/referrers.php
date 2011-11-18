<?php
namespace lib\entity;

class referrers extends \lib\entity {

  protected static $database = 'logs';
  protected static $table = 'referrers';

  public static function log() {
    $params = array();
    if (!isset($_SERVER['HTTP_REFERER'])) {
      return false;
    }

    $params['referrer_uri'] = $_SERVER['HTTP_REFERER'];
    $params['referrer_hash'] = self::hash($_SERVER['HTTP_REFERER']);
    \lib\database::enable_log(10);
    return self::create($params);
  }

  private static function hash($referrer) {
    return sha1($referrer);
  }

}
