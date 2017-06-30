<?php
define('SERVER', 'http://153.121.60.215:8080/');
define('SAKUSAKUNURL', SERVER.'RJServer/');
define('SAKU_PRJCD', 'DOTTAILOR');
define('SAKU_USERID', 'admin');
define('SAKU_PASSWORD', 'T3Password!!');
date_default_timezone_set('Asia/Tokyo');
function h($str){
    if(is_array($str)){
        return array_map("h",$str);
    }else{
        return htmlspecialchars($str,ENT_QUOTES,"UTF-8");
    }
}
function connect() {

  $options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
  );
  $pdo = new PDO(
    'mysql:host=localhost;dbname=t3_db',
    'root', 't3123', $options
  );
  // $pdo = new PDO("mysql:dbname=t3_db;host=localhost;charset=utf8", "root", "t3123");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $pdo;
}
function echo_filedate($filename) {
  if (file_exists($filename)) {
    return date('YmdHis', filemtime($filename));
  } else {
    return 'file not found';
  }
}
function post($key,$default='') {
    $v = (string)filter_input(INPUT_POST, $key);
    $v = $v !== '' ? $v : $default;
    return $v;
}

/**
 * さくさくんアクセス
 * @param  str $method 'insert'or'update'or'delete'or'auth'
 * @param  str $data   クエリストリング
 * @return bool        結果
 */
function sakusakun($data,$method){
  $url  = SAKUSAKUNURL."PrismApi?method=".$method;
  $headers = array(
      'Content-Type: application/x-www-form-urlencoded',
  );
  $params = array(
    'prjCd'    => SAKU_PRJCD,
    'userId'   => SAKU_USERID,
    'password' => SAKU_PASSWORD,
    'body'     => $data,
  );
  $context = stream_context_create(array(
      'http' => array(
          'method'        => 'POST',
          'header'        => implode("\n", $headers),
          'ignore_errors' => true,
          'content'       => http_build_query($params, '', '&'),
      ),
  ));
  $result = file_get_contents( $url, false, $context );
  return json_decode($result);
}
if (!function_exists('array_column')) {
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();
        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }
        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }
            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }
            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }
}
