<?php
namespace lib;

/**
 * Base class for ORM
 * Child class needs to specify:
 *   protected static $database
 *   protected static $table
 *
 * No functions need to be overridden
 */

abstract class entity extends attributes {

  private $model; // holds the model for the entity
  private $class; // cache which class was used for this instance

  /**
   * Constructor to be called via one of the static functions below
   */
  protected function __construct(array $params = array()) {
    $this->class = $class = get_called_class();
    $this->model = \lib\conf\models::${$class::$database}[$class::$table];

    parent::__construct(array_merge($this->model['defaults'], $params));
  }

  /**
   * Create new entity with parameters
   */
  public static function create(array $params = array()) {
    $class = get_called_class();
    $entity = new $class($params);
    $entity->__create();
    return $entity;
  }

  /**
   * Get entity record from database via primary_key (usually id) and where clause
   */
  public static function retrieve($id, $field = 'id', array $where = array()) {
    $class = get_called_class();
    $entity = new $class();
    if (!$entity->__fetch($id, $field, $where)) {
      return false;
    }
    return $entity;
  }

  /**
   * Gets iterator for the object
   */
  public static function get_iterator($where = '', $params = array(), $order = 'ASC') {
    if (!empty($where)) {
      $where = "WHERE $where";
    }
    if (empty($params)) {
      $params = array();
    }
    $class = get_called_class();

    $model = \lib\conf\models::${$class::$database}[$class::$table];

    $database = new database($class::$database);
    $result = $database->query("SELECT {$model['primary_key']['field']} AS primary_key FROM {$class::$table} $where ORDER BY primary_key $order", $params);
    $primary_keys = array();
    while ($row = mysql_fetch_assoc($result)) {
      $primary_keys[] = $row['primary_key'];
    }

    return new entity_iterator($primary_keys, $class);
  }

  /**
   * Save changes made to entity
   * Returns true when new data is save into the database, false otherwise
   */
  public function save() {
    if (!$this->is_changed()) {
      return true;
    }
    return $this->__update();
  }

  /**
   * Delete the entity
   */
  public function delete() {
    $success = $this->__delete();
    $this->reset();
    return $success;
  }




  /**
   * Helper function to insert into database
   */
  private function __create() {
    $class = $this->class;

    $attributes = $this->get_attributes();

    // auto populate date_added and date_updated
    $attributes['date_added'] = $attributes['date_updated'] = 'UNIX_TIMESTAMP()';

    $keys = array_keys($this->model['fields']);

    // check primary key possibilities
    if ($this->model['primary_key']['auto_increment']) {
      // primary key cannot be set if it is auto_increment
      if (!empty($attributes[$this->model['primary_key']['field']])) {
        log::error('cannot create with auto_increment key filled in: table: ' . $class::$table . ' field: ' . $this->model['primary_key']['field']);
        return false;
      }
    } else {
      // primary key must be filled in if not auto_increment
      if (empty($attributes[$this->model['primary_key']['field']])) {
        log::error('primary key unspecified: table: ' . $class::$table . ' field: ' . $this->model['primary_key']['field']);
        return false;
      }
    }

    $placeholders = $values = array();
    foreach ($keys as $key) {
      // if primary is auto_increment, then skip it
      if ($this->model['primary_key']['auto_increment'] && $this->model['primary_key']['field'] == $key) {
        continue;
      }

      if (!isset($this->model['fields'][$key])) {
        continue;
      }

      $column_list[] = $key;
      $placeholders[] = $this->model['fields'][$key];
      $values[] = $attributes[$key];
    }

    $column_list = '(`' . implode('`, `', $column_list) . '`)';
    $placeholders = '(' . implode(', ', $placeholders) . ')';

    $database = new database($class::$database);
    $database->query("INSERT INTO `{$class::$table}` $column_list VALUES $placeholders ON DUPLICATE KEY UPDATE date_updated = UNIX_TIMESTAMP()", $values);

    if ($this->model['primary_key']['auto_increment']) {
      $primary_key = mysql_insert_id($database->conn());
    } else {
      $primary_key = $attributes[$this->model['primary_key']['field']];
    }
    $this->__fetch($primary_key);
  }

  /**
   * Helper function to update into database
   */
  private function __update() {
    $class = $this->class;

    $attributes = $this->get_attributes();
    // do not update the primary key
    $primary_key = $attributes[$this->model['primary_key']['field']];
    unset($attributes['date_added'], $attributes[$this->model['primary_key']['field']]);
    $attributes['date_updated'] = 'UNIX_TIMESTAMP()';

    $keys = array_keys($attributes);
    $set_columns = array();
    foreach ($keys as $key) {
      $set_columns[] = "`$key` = " . $this->model['fields'][$key];
    }
    $set_columns = 'SET ' . implode(', ', $set_columns);

    $attributes[] = $primary_key;

    $database = new database($class::$database);
    $database->query("UPDATE `{$class::$table}` $set_columns WHERE `" . $this->model['primary_key']['field'] . '` = ' . $this->model['fields'][$this->model['primary_key']['field']], $attributes);
    $this->__fetch($primary_key);
  }

  /**
   * Helper function to fetch from database
   */
  private function __fetch($search_key, $field = false, array $where = array()) {
    $class = $this->class;
    $search_keys = array($search_key);

    if (!$field) {
      $field = $this->model['primary_key']['field']; // actual default field
    }

    $placeholders = array();
    foreach ($where as $key => $value) {
      $placeholders[] = "`$key` = " . $this->model['fields'][$key];
      $search_keys[] = $value;
    }
    $where = '';
    if (!empty($placeholders)) {
      $where = ' AND ' . implode(' AND ', $placeholders);
    }

    $attributes = $this->get_attributes();
    $database = new database($class::$database);
    $res = $database->query("SELECT * FROM `{$class::$table}` WHERE `$field` = " . $this->model['fields'][$field] . " $where", $search_keys);
    $params = mysql_fetch_assoc($res);
    if (!empty($params)) {
      $this->reset($params);
      return true;
    }
    return false;
  }

  /**
   * Helper function to delete from database
   */
  private function __delete() {
    $class = $this->class;

    $database = new database($class::$database);
    $database->query("DELETE FROM `{$class::$table}` WHERE `" . $this->model['primary_key']['field'] . '` = %s LIMIT 1', array($this[$this->model['primary_key']['field']]));
    return true;
  }

}
