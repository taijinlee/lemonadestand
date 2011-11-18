<?php
namespace lib;

abstract class redirect {


  /**
   * Redirect home
   */
  public static function home() {
    self::full(\lib\conf\constants::$domain);
  }

  /**
   * Full redirect meaning this request will end after this is called
   */
  public static function full($location) {
    if (preg_match('/^https?:/', $location)) {
      // make sure doamin is us
    } else {
      if (!empty($_SERVER['HTTPS'])) {
        $location = 'https://' . $location;
      } else {
        $location = 'http://' . $location;        
      }
    }

    header("Location: $location");
    exit;
  }

}
