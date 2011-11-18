<?php
namespace lib\entity;

class page_view extends \lib\entity {

  protected static $database = 'logs';
  protected static $table = 'page_views';

  public static function log() {
    $params = array();

    // $_SESSION['login_manager'] should be set
    if (!$GLOBALS['login_manager']) {
      \lib\log::error('Login manager not set');
    }

    $params['entity_id'] = $GLOBALS['login_manager']->get_user_id();
    $params['entity_type'] = $GLOBALS['login_manager']->get_tier();
    $params['session_id'] = session_id();
    $params['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
    $params['uri'] = $_SERVER['REQUEST_URI'];

    $params['referrer_hash'] = false;
    if ($referrer = referrers::log()) {
      $params['referrer_hash'] = $referrer->referrer_hash;
    }

    return self::create($params);
  }

}
