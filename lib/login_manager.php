<?php
namespace lib;

/**
 * Internal login functions
 */

abstract class login_manager {

  private $tier;
  private $login_page_url;
  private $login_required = true;

  public function __construct($tier, $login_page_url) {
    $this->tier = $tier;
    $this->login_page_url = $login_page_url;
  }

  /**
   * Internal application user login
   * This authenticates the user in our system
   */
  public function do_internal_login($user_id) {
    // set session variable
    $_SESSION[$this->tier]['user_login'] = $user_id;
    return true;
  }

  /**
   * Internal application user logout
   * This unauthenticates the user in our system
   */
  public function do_internal_logout() {
    // remove session variable
    unset($_SESSION[$this->tier]['user_login']);
  }

  /**
   * Internal application user login check
   * Returns true if the user is logged in internally. False otherwise
   */
  public function is_logged_in() {
    return $this->get_user_id() ? true : false;
  }

  /**
   * Gets internal user_id
   */
  public function get_user_id() {
    if (!isset($_SESSION[$this->tier]['user_login'])) {
      return false;
    }
    return $_SESSION[$this->tier]['user_login'];
  }

  /**
   * Internal login not required
   * Lets the view know that the page does not require login
   */
  public function login_not_required() {
    $this->login_required = false;
  }

  /**
   * Checks for correct login credentials if necessary. If we don't have credentials and it is required, then we redirect to specified url
   */
  public function check_login($redirect_url = '') {
    // if login is not required, return true
    if (!$this->login_required) {
      return true;
    }

    // if login is required and we find good login credentials, return true
    if ($this->is_logged_in()) {
      return true;
    }

    // redirect to home
    if (empty($redirect_url)) {
      redirect::full($this->login_page_url);
    } else {
      redirect::full($redirect_url);
    }
  }

  public function get_tier() {
    return $this->tier;
  }

  /**
   * Get login data ... don't know if this is unnecessary
   */
  private function get_login_data() {
    // check cache, then check cookie, 
    // cookie::get();
  }

}
