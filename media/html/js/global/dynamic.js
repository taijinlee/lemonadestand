<?php
namespace media;

include_once $_SERVER['NP_ROOT'] . '/media/init.php';

class js extends classes\view_js {

  public function run() {
    $this->add_media_file('js/global/base.js');
    $this->render_media();
  }

}

$js = new js();
$js->run();
