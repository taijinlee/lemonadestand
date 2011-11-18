<?php

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

class entityTest extends PHPUnit_Framework_TestCase {

  protected function setUp() {
    // start with a clean slate for each test
    $unit_test = new \lib\database('unit_test');
    $unit_test->query('TRUNCATE database_test');
  }


  /**
   * @dataProvider entityTestClassRows
   */
  public function testCreateEntity($row1, $row2) {
    $entity = entityTestClass::create($row1);
    $entity_data = $entity->get_attributes();

    $this->assertEquals(true, time() < $entity_data['date_added'] + 5);

    unset($entity_data['id'], $entity_data['date_added'], $entity_data['date_updated']);
    $this->assertEquals($entity_data, $row1);
  }

  /**
   * @dataProvider entityTestClassRows
   * @depends testCreateEntity
   */
  public function testRetrieve($row1, $row2) {
    $entity1 = entityTestClass::create($row1);
    $entity2 = entityTestClass::create($row2);

    // testing positive case
    $entity = entityTestClass::retrieve($entity1['id'], $field = 'id', $where = array('int' => -345));
    $entity_data = $entity->get_attributes();
    $this->assertEquals(true, time() < $entity_data['date_added'] + 5);

    unset($entity_data['id'], $entity_data['date_added'], $entity_data['date_updated']);
    $this->assertEquals($entity_data, $row1);

    // testing negative case
    $entity = entityTestClass::retrieve($entity1['id'], $field = 'id', $where = array('unsigned_int' => 4832));
    $this->assertEquals($entity, false);

  }


  /**
   * @dataProvider entityTestClassRows
   * @depends testCreateEntity
   * @depends testRetrieve
   */
  public function testUpdate($row1, $row2) {
    $entity1 = entityTestClass::create($row1);
    $entity2 = entityTestClass::create($row2);

    sleep(3); // so that we can see that the date_updated got updated properly
    $entity = entityTestClass::retrieve($entity1['id']);
    $entity['varchar'] = $row1['varchar'] = 'some var char';
    $entity['blob'] = $row1['blob'] = 'some other blob';
    $entity->save();

    $entity_data = $entity->get_attributes();
    $this->assertEquals($entity_data['date_added'] == $entity_data['date_updated'], false);
    unset($entity_data['id'], $entity_data['date_added'], $entity_data['date_updated']);
    $this->assertEquals($entity_data, $row1);
  }


  /**
   * @dataProvider entityTestClassRows
   * @depends testCreateEntity
   * @depends testRetrieve
   */
  public function testDelete($row1, $row2) {
    $entity1 = entityTestClass::create($row1);
    $entity2 = entityTestClass::create($row2);

    $entity1_id = $entity1['id'];
    $entity1->delete();
    $entity = entityTestClass::retrieve($entity1_id);
    $this->assertEquals($entity, false);
  }


  /**
   * @dataProvider entityTestClassRows
   * @depends testCreateEntity
   */
  public function testIterator($row1, $row2) {
    $entity1 = entityTestClass::create($row1);
    $entity2 = entityTestClass::create($row2);

    $entities = entityTestClass::get_iterator($where = 'unsigned_int > %d', $params = array(10));
    foreach ($entities as $entity) {
      $entity_data = $entity->get_attributes();
      unset($entity_data['id'], $entity_data['date_added'], $entity_data['date_updated']);
      $entity_datas[] = $entity_data;
    }

    $this->assertEquals(count($entities), 2);
    $this->assertEquals($entity_datas[0], $row1);
    $this->assertEquals($entity_datas[1], $row2);

  }


  public function entityTestClassRows() {
    return array(
      array(array('varchar' => 'osi4uei%($(!}{N: }s0', 'int' => -345, 'unsigned_int' => 88847392, 'blob' => '!@#$%^&*()_+{}-=[]\;\':"./,<>?)', 'datetime' => '9985-03-27 23:13:31'),
            array('varchar' => '*$JS*3si401]', 'int' => 9166, 'unsigned_int' => 99894220, 'blob' => '!@#$%^&*()_+{}-=[]\;\':"./,<>?)', 'datetime' => '9985-03-27 23:13:31')),
    );
  }

}




class entityTestClass extends \lib\entity {

  protected static $database = 'unit_test';
  protected static $table = 'database_test';

}
