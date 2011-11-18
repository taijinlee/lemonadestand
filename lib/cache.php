<?php
namespace lib;

/**
 * Generic caching interface
 */

class cache {

  private $options;

  public function __construct($type) {
    $this->options['type'] = $type;
  }

  public function set($key, $value) {
    $cache_type = $this->options['type'];
    $cache_type::set($key, $value);
  }

  public function get($key) {
    $cache_type = $this->options['type'];
    return $cache_type::get($key);
  }

}
