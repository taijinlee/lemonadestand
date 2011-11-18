<?php
namespace lib;

/**
 * Default view class for all tiers. This is basically an interface with Twig
 * Every tier should extend this to override the constructor with a default tier
 *
 * Usage:
 *   class page extends \tier\classes\view
 *   $page = new page();
 *   $page->run(); // to be implemented by child class
 */

abstract class view {

  private $variables;
  protected $twig_environment;

  /**
   * Constructor defines where Twig templates live
   */
  public function __construct($tier) {
    // get Twig loader
    $template_paths = array($_SERVER['NP_ROOT'] . '/templates');
    $loader = new \Twig_Loader_Filesystem($template_paths);

    // get Twig Environment based on loader
    $this->twig_environment = new \Twig_Environment($loader, self::get_options());
    $this->variables = array();
  }

  /**
   * Sets a variable into the Twig template
   */
  protected function set($key, $value) {
    $this->variables[$key] = $value;
  }

  /**
   * Renders specified twig template. Full path from /templates folder is required
   */
  protected function render($template, $return = false) {
    $this->set('constants', get_class_vars('\lib\conf\constants'));
    $rendered = $this->twig_environment->render($template, $this->variables);
    if ($return) {
      return $rendered;
    } else {
      echo $rendered;
    }
    \lib\entity\page_view::log();
  }

  /**
   * Gets some Twig options from config files
   */
  private static function get_options() {
    $options = array('cache' => false, 'debug' => false, 'strict_variables' => true);

    if (\lib\conf\constants::$twig_cache_on) {
      $options['cache'] = $_SERVER['NP_ROOT'] . '/cache/twig/';
    }
    if (\lib\conf\constants::$twig_debug_on) {
      $options['debug'] = true;
    }

    return $options;
  }

}
