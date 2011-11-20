<?php
namespace build;

class constants extends build {

  protected static function _build($is_prod) {

    $params = array();

    // base hostname
    if ($is_prod) {
      $params['domain'] = 'lemonade.nopuku.com';
    } else {
      $user = trim(`whoami`);
      $params['domain'] = "{$user}.lemonade.nopuku.com";
    }

    // twig configs
    if ($is_prod) {
      // not using production for now
      $params['twig_cache_on'] = false;
      $params['twig_debug_on'] = true;
    } else {
      $params['twig_cache_on'] = false;
      $params['twig_debug_on'] = true;
    }

    // facebook application settings
    $params['fb_app_id'] = '134231423320970';
    $params['fb_app_secret'] = '417bd59c1f93235c1270deacb4791814';


    foreach ($params as $key => $value) {
      self::add_param($key, $value);
    }
  }

}
