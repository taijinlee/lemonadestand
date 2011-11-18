<?php
namespace lib;

/**
 * Entity Attribute Value (EAV) ORM
 * Given an Entity reference, we have a hash table for values
 *
 * Example: user_settings where table is user_id (E), setting_name (A), setting_value (v)
 *
 * Child class needs to provide:
 *  protected static $database, $table;
 *  protected static $entity_id_column, $key_column, $value_column;
 *
 */
abstract class entity_hash extends attributes {

  private $class; // cache which class was used for this instance
  private $model; // holds the model for the entity hash
  private $entity_id; // holds instance entity id
  private $original_keys; // keeps track of original keys so we can diff when unset
  

  protected function __construct($entity_id) {
    $this->class = $class = get_called_class();
    $this->model = \lib\conf\models::${$class::$database}[$class::$table];
    $this->entity_id = $entity_id;

    // get data from db
    $this->__fetch($entity_id);
    $this->original_keys = array_keys($this->get_attributes());
  }

  public static function retrieve($entity_id) {
    $class = get_called_class();
    return new $class($entity_id);
  }

  public function save() {
    return $this->__save();
  }



  /**
   * Fetches hashed data from db and put into instance
   */
  private function __fetch($entity_id) {
    $class = $this->class;

    $database = new database($class::$database);
    $res = $database->query("SELECT `{$class::$entity_id_column}`, `{$class::$key_column}`, `{$class::$value_column}` FROM `{$class::$table}` WHERE `{$class::$entity_id_column}` = {$this->model['fields'][$class::$entity_id_column]}", array($entity_id));

    $entity_hash = array();
    while ($row = mysql_fetch_assoc($res)) {
      $entity_hash[$row[$class::$key_column]] = $row[$class::$value_column];
    }
    $this->reset($entity_hash);
    return true;
  }

  /**
   * Fetches hashed data from db and put into instance
   */
  private function __save() {
    $class = $this->class;

    $database = new database($class::$database);
    if ($this->get_attributes()) {
      $columns = "(`{$class::$entity_id_column}`, `{$class::$key_column}`, `{$class::$value_column}`, `date_added`, `date_updated`)";

      $placeholders = $values = $duplicate_keys = array();
      foreach ($this->get_attributes() as $key => $value) {
        $placeholders[] = "(%d, %s, %s, NOW(), NOW())";
        $values[] = $this->entity_id;
        $values[] = $key;
        $values[] = $value;
        $duplicate_keys[] = "`{$class::$value_column}` = VALUES(`{$class::$value_column}`)";
      }
      $duplicate_keys[] = '`date_updated` = VALUES(`date_updated`)';

      $database->query("INSERT INTO {$class::$table} $columns VALUES " . implode(', ', $placeholders). " ON DUPLICATE KEY UPDATE " . implode(', ', $duplicate_keys), $values);
    }

    if ($unset_keys = array_diff($this->original_keys, array_keys($this->get_attributes()))) {
      // we unset something
      print_r(array($this->entity_id, $unset_keys));
      database::enable_log();
      $database->query("DELETE FROM {$class::$table} WHERE `{$class::$entity_id_column}` = %d AND `{$class::$key_column}` IN (%s)", array($this->entity_id, $unset_keys));
    }

    return true;
  }

}
