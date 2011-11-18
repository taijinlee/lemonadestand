<?php
namespace build;

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

build::all(false);

class build {

  public static function all($is_prod = true) {
    self::$build_dir = $_SERVER['NP_ROOT'] . '/lib/conf/';
    self::clean_dir(self::$build_dir);

    databases::build_conf($is_prod, $file_name = 'databases');
    models::build_conf($is_prod, $file_name = 'models');
    constants::build_conf($is_prod, $file_name = 'constants');
  }

  // to be overridden by child class
  protected static function _build($is_prod) { }

  private static $fh = false;
  private static $build_dir;

  public static function build_conf($is_prod, $file_name) {
    self::get_file_handler(self::$build_dir . $file_name . '.php');
    self::open($file_name);
    $class = get_called_class();
    $class::_build($is_prod);
    self::close();
  }

  protected static function add_param($key, $value) {
    $param_string = "  public static \${$key} = " . self::get_value_string($value) . ";\n\n";
    fwrite(self::$fh, $param_string);
  }



  private static function clean_dir($dir) {
    exec("rm -Rf $dir*");
  }


  private static function open($file_name) {
    fwrite(self::$fh, "<?php\nnamespace lib\conf;\n\nclass $file_name {\n\n");
  }

  private static function close() {
    fwrite(self::$fh, "}\n");
    fclose(self::$fh);
    self::$fh = null;
  }

  private static function get_file_handler($file_full_path) {
    if (self::$fh) {
      return;
    }

    self::$fh = fopen($file_full_path, 'w');
    if (!self::$fh) {
      \lib\log::error('Cannot open configuration file: ' . $file_full_path);
    }
  }

  // recursive
  private static function get_value_string($value) {
    if (!is_array($value)) {
      return "'$value'";
    }

    // is definitely array at this point
    foreach ($value as $key => $value) {
      $new_value = self::get_value_string($value);
      $string_array[] = "'$key' => $new_value";
    }

    return 'array(' . implode(', ', $string_array) . ')';
  }

}
