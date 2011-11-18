<?php
namespace lib;

/**
 * Paginates an seekable, iterable, countable object (array, entity_iterator, etc)
 */
class pager implements \Iterator, \Countable {

  private $seekableiterator_countable_object, $items_per_page, $page_number, $num_items, $num_pages;
  private $position; // position of page

  public function __construct($seekableiterator_countable_object, $items_per_page, $page_number) {
    if (is_array($seekableiterator_countable_object)) {
      $seekableiterator_countable_object = new ArrayIterator($seekableiterator_countable_object);
    }

    $this->seekableiterator_countable_object = $seekableiterator_countable_object;
    $this->items_per_page = $items_per_page;
    $this->page_number = $page_number;
    $this->num_items = count($seekableiterator_countable_object);
    $this->num_pages = $this->num_items / $items_per_page;
    if (!is_int($this->num_pages)) {
      $this->num_pages = (int) $this->num_pages + 1;
    }

    if ($this->num_pages != 0 && $this->page_number > $this->num_pages) {
      // something bad should happen here
      log::error('bad page number');
    }

    $this->current_url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

    $this->rewind();    
  }

  public function next_page_url() {
    return \lib\url::add_parameter($this->current_url, 'page', $this->next_page_number);
  }

  public function prev_page_url() {

  }



  public function current_page() {
    return $this->page_number;
  }

  public function num_pages() {
    return $this->num_pages;
  }

  public function has_next_page() {
    return $this->page_number < $this->num_pages;
  }

  public function has_previous_page() {
    return $this->page_number > 1;
  }

  public function next_page_number() {
    return $this->page_number + 1;
  }

  public function previous_page_number() {
    return $this->page_number - 1;
  }





  /**
   * Implements Iterator 
   */
  public function current() {
    return $this->seekableiterator_countable_object->current();
  }
  public function key() {
    return $this->seekableiterator_countable_object->key();
  }
  public function next() {
    $this->position += 1;
    return $this->seekableiterator_countable_object->next();
  }
  public function rewind() {
    $this->position = 0;
    $this->seekableiterator_countable_object->rewind();
    $this->seekableiterator_countable_object->seek($this->items_per_page * ($this->page_number - 1));
  }
  public function valid() {
    if ($this->position >= $this->items_per_page) {
      return false;
    }

    return $this->seekableiterator_countable_object->valid();
  }


  /**
   * Implements Countable
   */
  public function count() {
    return count($this->seekableiterator_countable_object);
  }

}
