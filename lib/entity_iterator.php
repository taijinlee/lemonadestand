<?php
namespace lib;

class entity_iterator implements \SeekableIterator, \Countable {

  private $objects, $position;
  private $entity_class;

  public function __construct($primary_keys, $class) {
    $this->primary_keys = $primary_keys;
    if (count($primary_keys)) {
      $this->objects = array_fill(0, count($primary_keys), null);
    } else {
      $this->objects = array();
    }
    $this->position = 0;

    $this->entity_class = $class;
  }


  /**
   * Implements Iterator
   */
  public function current() {
    if ($this->objects[$this->position] === false) {
      return false;
    }
    $object = $this->get_primary_key_object($this->primary_keys[$this->position]);

    $this->objects[$this->position] = ($object === false) ? false : true;
    return $object;
  }

  public function key() {
    return $this->position;
    // return $this->primary_keys[$this->position];
  }

  public function next() {
    $this->position++;
  }

  public function rewind() {
    $this->position = 0;
  }

  public function valid() {
    if (!isset($this->primary_keys[$this->position])) {
      return false;
    }
    $object = $this->get_primary_key_object($this->primary_keys[$this->position]);
    return ($object) ? true : false;
  }

  public function seek($position) {
    $this->position = $position;
  }


  /**
   * Implements Countable
   */
  public function count() {
    return count($this->primary_keys);
  }


  private function get_primary_key_object($primary_key) {
    $class = $this->entity_class;
    $object = $class::retrieve($primary_key);
    return $object;
  }

}
