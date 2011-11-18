<?php
namespace lib;

/**
 * Manipulates urls and url parameters
 */
class url {

  /**
   * Adds url parameter. If it already exists, this will overwrite existing parameter
   */
  public static function add_parameter($url, $name, $value) {
    list($parsed_url, $query) = self::parse_url($url);
    $query[$name] = $value;

    $parsed_url['query'] = http_build_query($query);
    return self::generate_url_from_parsed_url($parsed_url);
  }

  /**
   * Removes url parameter from the url.
   */
  public static function remove_parameter($url, $name) {
    list($parsed_url, $query) = self::parse_url($url);
    unset($query[$name]);

    $parsed_url['query'] = http_build_query($query);
    return self::generate_url_from_parsed_url($parsed_url);
  }

  /**
   * Parses query and returns the parsed query along with array of query parameters in name => value array
   */
  private static function parse_url($url) {
    $parsed_url = parse_url($url);

    $query = array();
    if (!isset($parsed_url['query'])) {
      return array($parsed_url, $query);
    }

    foreach (explode('&', $parsed_url['query']) as $query_variable) {
      list($variable_name, $variable_value) = explode('=', $query_variable);
      $query[$variable_name] = $variable_value;
    }
    return array($parsed_url, $query);
  }

  /**
   * Given a parsed url array from parse_url, returns full url string
   */
  private static function generate_url_from_parsed_url($parsed_url) {
    $url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];
    if (!empty($parsed_url['query'])) {
      $url .= '?' . $parsed_url['query'];
    }
    if (!empty($parsed_url['fragment'])) {
      $url .= '#' . $parsed_url['fragment'];
    }
    return $url;
  }

}
