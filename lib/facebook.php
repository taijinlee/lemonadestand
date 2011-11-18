<?php
namespace lib;

/**
 * Facebook Interface
 */
class facebook extends facebook\facebook {

  private static $facebook = false;

  /**
   * Gets an instance of this class. We only want one instance of this class globally, so forcing factory method
   */
  public static function get_facebook_interface() {
    if (!self::$facebook) {
      self::$facebook = new self();
    }
    return self::$facebook;
  }

  /**
   * Constructor. See get_facebook_interface to instantiate this class
   */
  protected function __construct() {
    if (!self::$facebook) {
      $config = array('appId' => \lib\conf\constants::$fb_app_id, 'secret' => \lib\conf\constants::$fb_app_secret);
      parent::__construct($config);
      self::$facebook = $this;
    }
  }






  /**
   * Get Facebook user data
   */
  public function get_user_info() {
    if (!$this->getUser()) {
      return false;
    }

    return $this->api('/me');
  }


  /**
   * Checks to see if user is logged into facebook with proper permissions
   */
  public function is_logged_in() {
    // check if user is logged into facebook
    if ($this->getUser()) {
      return true;
    }
    return false;
  }

}
