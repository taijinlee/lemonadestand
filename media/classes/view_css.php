<?php
namespace media\classes;

class view_css extends \lib\view {

  private $css_files;

  /**
   * Initialize view with user tier specifics
   */
  public function __construct() {
    $this->css_files = array();

    // calling parent specifying media tier
    parent::__construct('media');
  }

  /**
   * Adds a css file into the current group
   */
  public function add_css_file($file_name) {
    $this->css_files[] = $file_name;
  }

  /**
   * Overriding rendering functionality just for css
   */
  protected function render_media() {
    $combined_css = array();
    foreach ($this->css_files as $css_file) {
      $combined_css[] = parent::render($css_file, $return = true);
    }
    header('Content-type: text/css');
    echo implode("\n", $combined_css);
  }

}
