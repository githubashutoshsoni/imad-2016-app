<?php
/***************************************
  * http://www.program-o.com
  * PROGRAM O
  * Version: 2.6.3
  * FILE: misc_functions.php
  * AUTHOR: Elizabeth Perreau and Dave Morton
  * DATE: 05-22-2013
  * DETAILS: Miscelaneous functions for Program O
  ***************************************/

  /**
   * function get_cURL
   * Uses PHP's cURL functions to obtain data from "outside locations"
   *
   * @param (string) $url - The URL or IP address to access
   * @param array $options
   * @param array $params
   *
   * @return mixed|string (string) $out - The returned value from the curl_exec() call.
   */
  function get_cURL($url, $options = array(), $params = array())
  {
    $failed = 'Cannot process CURL call.'; // This will need to be changed, at some point.
    if (function_exists('curl_init')) {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      if (is_array($options) and count($options) > 0)
      {
        foreach ($options as $key => $value)
        {
          curl_setopt($ch, $key, $value);
        }
      }
      if (is_array($params) and count($params) > 0)
      {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
      }
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
    }
    else return $failed;
  }

  /**
   * function _strtolower
   * Performs multibyte or standard lowercase conversion of a string,
   * based on configuration.
   *
   * @param string $text The string to convert.
   * @return string The input string converted to lower case.
   */
  function _strtolower($text) {
    return (IS_MB_ENABLED) ? mb_strtolower($text) : strtolower($text);
  }

  /**
   * function _strtoupper
   * Performs multibyte or standard uppercase conversion of a string,
   * based on configuration.
   *
   * @param string $text The string to convert.
   * @return string The string converted to UPPER CASE.
   */
  function _strtoupper($text) {
    return (IS_MB_ENABLED) ? mb_strtoupper($text) : strtoupper($text);
  }

  /**
   * function _title_case
   *
   * Performs multibyte or standard uppercase conversion of the first character of a string,
   * based on configuration.
   *
   * @param string $text The string to convert.
   * @return string The string converted to Title Case.
   */
  function  _title_case($text) {
    global $charset;
    return (IS_MB_ENABLED) ? mb_convert_case($text, MB_CASE_TITLE, $charset) : ucwords($text);
  }

  /**
   * function _substr
   *
   * Gets the substring of either single byte or multi-byte strings by index and length, depending on configuration
   *
   * @param string $text
   * @param string $start
   * @param string $len
   * @param string $encoding
   *
   * @return string
   */
   function _substr($text, $start, $len = null, $encoding = null) {
     if ($encoding === null) $encoding = mb_internal_encoding();
     return (IS_MB_ENABLED) ? mb_substr($text, $start, $len, $encoding) : substr($text, $start, $len);
   }

  /**
   * function normalize_text
   * Transforms text to uppercase, removes all punctuation, and strips extra whitespace
   *
   * @param (string) $text - The text to perform the transformations on
   * @return mixed|string (string) $normalized_text - The completely transformed text
   */
    function normalize_text($text)
    {
      runDebug(__FILE__, __FUNCTION__, __LINE__,"Begin normalization - text = '$text'", 4);
      $normalized_text = preg_replace('/(\d+) - (\d+)/', '$1 MINUS $2', $text);
      $normalized_text = preg_replace('/(\d+)-(\d+)/', '$1 MINUS $2', $normalized_text);
      $normalized_text = preg_replace('/(\d+) \+ (\d+)/', '$1 PLUS $2', $normalized_text);
      $normalized_text = preg_replace('/(\d+)\+(\d+)/', '$1 PLUS $2', $normalized_text);
      $normalized_text = preg_replace('/(\d+) \* (\d+)/', '$1 TIMES $2', $normalized_text);
      $normalized_text = preg_replace('/(\d+) x (\d+)/', '$1 TIMES $2', $normalized_text);
      $normalized_text = preg_replace('/(\d+)x(\d+)/', '$1 TIMES $2', $normalized_text);
      $normalized_text = preg_replace('/(\d+)\*(\d+)/', '$1 TIMES $2', $normalized_text);
      $normalized_text = preg_replace('/(\d+) \/ (\d+)/', '$1 DIVIDEDBY $2', $normalized_text);
      $normalized_text = preg_replace('/(\d+)\/(\d+)/', '$1 DIVIDEDBY $2', $normalized_text);
      $normalized_text = preg_replace('/[[:punct:]]/uis', ' ', $normalized_text);
      $normalized_text = preg_replace('/\s\s+/', ' ', $normalized_text);
      $normalized_text = _strtoupper($normalized_text);
      $normalized_text = trim($normalized_text);
      runDebug(__FILE__, __FUNCTION__, __LINE__,"Normalization complete. Text = '$normalized_text'", 4);
      return $normalized_text;
    }

  /**
   * Function getFooter
   *
   *
   * @return string
   */
  function getFooter() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $name = (isset($_SESSION['poadmin']['name'])) ?  $_SESSION['poadmin']['name'] : 'unknown';
    $lip = (isset($_SESSION['poadmin']['lip'])) ?  $_SESSION['poadmin']['lip'] : 'unknown';
    $last = (isset($_SESSION['poadmin']['last_login'])) ?  $_SESSION['poadmin']['last_login'] : 'unknown';
    $llast = (isset($_SESSION['poadmin']['prior_login'])) ?  $_SESSION['poadmin']['prior_login'] : 'unknown';
    $admess = "You are logged in as: $name from $ip since: $last";
    $admess .= "<br />You last logged in from $lip on $llast";
    $today = date("Y");
    $out = <<<endFooter
    <p>&copy; $today My Program-O<br />$admess</p>
endFooter;
    return $out;
  }

  /*
   * function session_gc
   * Performs garbage collection on expired session files
   *
   * @return (void)
   */
  function session_gc()
  {
    return false;
/*
    This function is temporarily disabled until I can devise a better implementation of session handling

    global $session_lifetime;
    $session_files = glob(_SESSION_PATH_ . 'sess_*');
    foreach($session_files as $file)
    {
      $lastAccessed = filemtime($file);
      if ($lastAccessed < (time() - $session_lifetime)) unlink($file);
    }
*/
  }

  function addUnknownInput($convoArr, $input, $bot_id, $user_id) {
    global $dbConn, $dbn;
    $default_aiml_pattern = get_convo_var($convoArr, 'conversation', 'default_aiml_pattern');
    if ($input == $default_aiml_pattern) return false;
    $sql = "insert into `$dbn`.`unknown_inputs` (`id`, `bot_id`, `input`, `user_id`, `timestamp`) values(null, :bot_id, :input, :user_id, CURRENT_TIMESTAMP);";
    $params = array(
      ':bot_id' => $bot_id,
      ':input' => $input,
      ':user_id' => $user_id,
    );
    $numRows = db_write($sql, $params, false, __FILE__, __FUNCTION__, __LINE__);
  }
