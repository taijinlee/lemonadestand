<?php
namespace media\classes;

class view extends \lib\view {

  private $media_files;

  /**
   * Initialize view with user tier specifics
   */
  public function __construct() {
    $this->media_files = array();

    // calling parent specifying media tier
    parent::__construct('media');
  }

  /**
   * Adds a media file into the current group
   */
  public function add_media_file($file_name) {
    $this->media_files[] = $file_name;
  }

  /**
   * Overriding rendering functionality just for rendering media
   */
  protected function render_media() {
    $combined_media = array();
    foreach ($this->media_files as $media_file) {
      $combined_media[] = parent::render($media_file, $return = true);
    }
    echo implode("\n", $combined_media);
  }

}
