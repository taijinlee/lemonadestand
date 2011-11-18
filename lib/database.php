<?php
namespace lib;

/**
 * Handles all connections with database
 */

class database {

  private static $log = 0;

  private $conn = false;
  private static $connections = array();

  public function __construct($database) {
    // grab from static cache first
    if (isset(self::$connections[$database])) {
      $this->conn = self::$connections[$database];
      return;
    }

    $host = \lib\conf\databases::${$database}['host'];
    $user = \lib\conf\databases::${$database}['user'];
    $pass = \lib\conf\databases::${$database}['password'];

    $this->conn = mysql_connect($host, $user, $pass, $new_link = true);
    if (!$this->conn) {
      log::error('Database connection cannot be established: ' . $database);
      return false;
    }
    mysql_select_db($database, $this->conn);
    self::$connections[$database] = $this->conn;
  }

  public function conn() {
    return $this->conn;
  }

  public function query($sql, $args = array()) {
    $sql = $this->generate_sql($sql, $args);

    if (self::$log > 0) {
      self::$log--;
      log::debug($sql);
    }

    $result = mysql_query($sql, $this->conn);
    if ($error = mysql_error()) {
      log::error($error);
      return false;
    }
    return $result;
  }

  public static function enable_log($num_logs = 1) {
    self::$log = $num_logs;
  }

  private function generate_sql($sql, $args) {
    $offset = 0;
    foreach ($args as $key => &$arg) {
      $offset = strpos($sql, '%', $offset);
      if (is_array($arg)) {
        // recursively generate the sql if it is a nested array
        $placeholders = array_fill(0, count($arg), "%{$sql[$offset + 1]}");
        $arg = $this->generate_sql(implode(', ', $placeholders), $arg);
        // using an unescaped string at this point
        $sql[$offset + 1] = 'S';
      } else {
        // otherwise just escape the argument
        $arg = mysql_real_escape_string($arg, $this->conn);
      }
      $offset++;
    }
    unset($arg);

    // adding ' to string arguments
    $sql = str_replace('%s', '\'%s\'', $sql);
    // %S does not auto add quotes
    $sql = str_replace('%S', '%s', $sql);

    return vsprintf($sql, $args);
  }

}
