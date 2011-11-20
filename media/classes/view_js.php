<?php
namespace media\classes;

class view_js extends view {

  protected function render_media() {
    header('Content-type: text/js');
    parent::render_media();
  }

}
