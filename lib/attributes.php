<?php
namespace lib;

/**
 * Attributes class holds a private array for any class extending it.
 * It also implements array access ($object['variable'])
 * and magic get / set interfaces ($object->variable)
 */
abstract class attributes implements \ArrayAccess {
  private $attributes = array(); // holds the variables of the object
  private $is_changed = false;

  protected function __construct($params) {
    // populate with params
    $this->reset($params);
  }

  public function get_attributes() {
    return $this->attributes;
  }

  public function touch() {
    $this->is_changed = true;
  }

  public function is_changed() {
    return $this->is_changed;
  }

  protected function reset(array $params = array()) {
    $this->set_attributes($params);
    $this->is_changed = false;
  }

  protected function set_attributes($params) {
    $this->attributes = (is_array($params) && !empty($params)) ? $params : array();
    $this->touch();
  }


  // implement *magic* interface (ie. $this->variable_name)
  public function __set($key, $value) {
    if (!isset($this->attributes[$key]) || $this->attributes[$key] != $value) {
      $this->attributes[$key] = $value;
      $this->touch();
    }
  }

  public function __get($key)  {
    return $this->attributes[$key];
  }

  public function __unset($key) {
    if (isset($this->attributes[$key])) {
      unset($this->attributes[$key]);
      $this->touch();
    }
  }




  // implement ArrayAccess interface
  public final function offsetSet($offset, $value) {
    $this->__set($offset, $value);
  }

  public final function offsetGet($offset) {
    return $this->__get($offset);
  }

  public final function offsetUnset($offset) {
    $this->__unset($offset);
  }

  public final function offsetExists($offset) {
    return array_key_exists($offset, $this->attributes);
  }

}
