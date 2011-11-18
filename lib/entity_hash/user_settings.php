<?php
namespace lib\entity_hash;

class user_settings extends \lib\entity_hash {

  protected static $database = 'rez', $table ='user_settings';
  protected static $entity_id_column = 'user_id', $key_column = 'setting_name', $value_column = 'setting_value';

}
