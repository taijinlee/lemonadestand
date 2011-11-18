<?php
namespace build;

class models extends build {

  protected static function _build($is_prod) {
    $databases = databases::get_databases($is_prod);

    foreach ($databases as $database => $params) {
      $conn = mysql_connect($params['host'], $params['user'], $params['password'], $new_link = true);
      mysql_select_db($database, $conn);

      $tables = self::get_tables($conn);
      $tables_info = array();
      foreach ($tables as $table) {
        $tables_info[$table] = self::get_table_info($conn, $table);
      }

      if (!empty($tables_info)) {
        self::add_param($database, $tables_info);
      }
    }
  }

  private static function get_tables($conn) {
    $res = mysql_query('SHOW tables', $conn);
    $tables = array();
    while($row = mysql_fetch_assoc($res)) {
      $tables[] = current($row);
    }
    return $tables;
  }

  private static function get_table_info($conn, $table) {
    $table_info = array();

    $table = mysql_real_escape_string($table);
    $res = mysql_query("DESC $table");
    while ($row = mysql_fetch_assoc($res)) {
      list($placeholder, $primary_key) = self::get_row_info($row);
      if ($primary_key) {
        $table_info['primary_key']['field'] = $row['Field'];
        $table_info['primary_key']['auto_increment'] = $row['Extra'] == 'auto_increment';
      }
      
      $table_info['fields'][$row['Field']] = $placeholder;
      $table_info['defaults'][$row['Field']] = $row['Default'];
    }

    return $table_info;
  }

  private static function get_row_info($row) {
    if ($row['Field'] == 'date_added' || $row['Field'] == 'date_updated') { // this gets hardcoded by UNIX_TIMESTAMP() in ORM
      $placeholder = '%S';
    } elseif (strpos($row['Type'], 'int')) {
      $placeholder = '%d';
    } elseif (strpos($row['Type'], 'float') || strpos($row['Type'], 'decimal') || strpos($row['Type'], 'double')) {
      $placeholder = '%f';
    } else {
      $placeholder = '%s';
    }

    $primary_key = ($row['Key'] == 'PRI');

    return array($placeholder, $primary_key);
  }

}
