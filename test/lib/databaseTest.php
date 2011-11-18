<?php

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

class databaseTest extends PHPUnit_Framework_TestCase {

  public static function setupBeforeClass() {
    exec('/service/app/php/bin/php ' . $_SERVER['NP_ROOT'] . '/build/build.php');
  }

  public static function tearDownAfterClass() {
    $unit_test = new \lib\database('unit_test');
    $unit_test->query('TRUNCATE database_test');
    exec('/service/app/php/bin/php ' . $_SERVER['NP_ROOT'] . '/build/build.php');
  }

  public function testConnect() {
    foreach (get_class_vars('\\lib\\conf\\databases') as $database_name => $database_credentials) {
      $database = new \lib\database($database_name);
      if ($database_name == 'unit_test_bad_database') {
        $this->assertEquals(is_resource($database->conn()), false);
      } else {
        $this->assertEquals(is_resource($database->conn()), true);
      }
    }
  }

  public function testQuery() {
    $data = array(
      'id' => 5,
      'varchar' => 'osi4uei%($(!}{N: }s0',
      'int' => -345,
      'unsigned_int' => 88847392,
      'blob' => '!@#$%^&*()_+{}-=[]\;\':"./,<>?)',
      'date_added' => 'UNIX_TIMESTAMP()',
      'date_updated' => 'UNIX_TIMESTAMP()'
    );

    $unit_test = new \lib\database('unit_test');
    $unit_test->query('TRUNCATE database_test');
    $sql = 'INSERT INTO database_test (`id`, `varchar`, `int`, `unsigned_int`, `blob`, `date_added`, `date_updated`) VALUES (%d, %s, %d, %d, %s, %S, %S)';

    \lib\database::enable_log();
    ob_start();
    $unit_test->query($sql, $data);
    $output = ob_get_contents();
    ob_end_clean();

    $expected = "INSERT INTO database_test (`id`, `varchar`, `int`, `unsigned_int`, `blob`, `datetime`) VALUES (5, 'osi4uei%($(!}{N: }s0', -345, 88847392, '!@#$%^&*()_+{}-=[]\\\;\\':\\\"./,<>?)')";
    // TODO: figure out how to reenabled this with the least amount of intrustion to \lib\database.php
    // $this->assertEquals($expected, $output);

    $db_data = mysql_fetch_assoc($unit_test->query('SELECT `id`, `varchar`, `int`, `unsigned_int`, `blob`, `date_added` FROM database_test WHERE id = ' . $data['id']));
    $this->assertEquals(true, time() < $db_data['date_added'] + 5);

    unset($db_data['date_added'], $db_data['date_updated'], $data['date_added'], $data['date_updated']);
    $this->assertEquals($data, $db_data);
  }


  public function testBadQuery() {
    $unit_test = new \lib\database('unit_test');

    $this->assertEquals(false, $unit_test->query('SELECT * FROM'));

  }

}
