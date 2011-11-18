<?php
namespace lib;

/**
 * Token generation for time limited authentication
 * You need the time of authorization and the token itself to match
 */

class token {

  const HASH_CYCLES = 2694;

  /**
   * Generates a token, time to live can be 0 which means forever
   */
  public static function generate($tokenize, $salt, $time, $time_to_live) {
    $hashed_salt = self::hash($salt);
    return self::hash("$tokenize:$hashed_salt:$time:$time_to_live");
  }

  /**
   * Returns true or false based on whether or not the token matches within the time range
   */
  public static function match($token, $tokenize, $salt, $time, $time_to_live) {
    if ($token == self::generate($tokenize, $salt, $time, $time_to_live)) {
      if ($time_to_live && (time() < $time + $time_to_live)) {
        // within the time to live range
        return true;
      } else if (!$time_to_live) {
        // no time to live
        return true;        
      }
    }
    return false;
  }


  /**
   * Hashes the string
   */
  private static function hash($token) {
    $hash_cycle = 0;
    while ($hash_cycle < self::HASH_CYCLES) {
      $token = sha1($token);
      $hash_cycle++;
    }
    return $token;
  }

}
