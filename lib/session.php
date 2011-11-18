<?php
namespace lib;

class session {

  public static function init() {
    // Register this object as the session handler
    session_set_save_handler(array('\lib\session', "open"), array('\lib\session', "close"), array('\lib\session', "read"),
                             array('\lib\session', "write"), array('\lib\session', "destroy"), array('\lib\session', "gc"));
  }

  public static function open($save_path, $session_name) {
    return true;
  }

  public static function close() {
    return true;
  }

  public static function read($session_id) {
    $session = entity\session::retrieve($session_id);
    if (!$session) {
      return '';
    }
    return $session['data'];
  }

  public static function write($session_id, $data) {
    if (!($session = entity\session::retrieve($session_id))) {
      entity\session::create(array('id' => $session_id, 'data' => $data));
    } else {
      $session['data'] = $data;
      $session->save();
    }
    return true;
  }

  public static function destroy($session_id) {
    if ($session = entity\session::retrieve($session_id)) {
      $session->delete();
      return true;
    }
    return false;
  }

  public static function gc($max_lifetime) {
    // do this manually via cron job instead
    return true;
  }

}
