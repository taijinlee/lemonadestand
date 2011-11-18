<?php
namespace user\classes;

/**
 * Login manager for user tier
 */

class login_manager extends \lib\login_manager {

  private $facebook;

  /**
   * Creates all the necessary interfaces for login / logout functions
   */
  public function __construct() {
    $this->facebook = \lib\facebook::get_facebook_interface();
    parent::__construct('user', \lib\conf\constants::$domain . '/login');
  }

  /**
   * Get facebook login url
   */
  public function get_facebook_login_url() {
    $domain = \lib\conf\constants::$domain;
    $params['redirect_uri'] = "http://{$domain}/login/facebook/";
    $params['display'] = 'popup';
    $params['scope'] = 'email';
    return $this->facebook->getLoginUrl($params);
  }

  /**
   * Facebook logout url. Note: the url generated will allow users to logout of facebook, but not our internal system
   */
  public function get_facebook_logout_url() {
    return $this->facebook->getLogoutUrl();
  }

  /**
   * If user is logged in via facebook, then login the specific user internally
   */
  public function do_facebook_login($user_id) {
    if (!$this->facebook->is_logged_in()) {
      return false;
    }
    return $this->do_internal_login($user_id);
  }

}
