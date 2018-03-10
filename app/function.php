<?php
session_start();
//判断是否登陆
function is_login(){
//	if (isset($_COOKIE['is_login']) && $_COOKIE['is_login'] == 1) {
//    return true;
//  }else{
//    return false;
//  }
    if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == 1) {
        return true;
    }else{
        return false;
    }
}
//判断是否输入二级密码
function is_checkSPD(){
    if (isset($_SESSION['is_checkSPD']) && $_SESSION['is_checkSPD'] == 1) {
        return true;
    }else{
        return false;
    }
}
//判断是否是post
function is_post(){
  if (strtolower($_SERVER['REQUEST_METHOD']) != 'get') {
    return true;
  }else{
    return false;
  }
}

function select($table,$where=false,$field=false,$limit=false,$order = false){
  require 'config.php';
  $db = new mysqli($config['database']['ip'],$config['database']['user'],$config['database']['password'],$config['database']['database'],$config['database']['port']);
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }
  $db->query("set names 'UTF8'");
  if ($where) {
    $where_str = "where {$where}";
  }else{
    $where_str = '';
  }
  if ($field) {
    $field_str = $field;
  }else{
    $field_str = '*';
  }
  if ($limit) {
    $limit_str = " limit {$limit}";
  }else{
    $limit_str = '';
  }
  if ($order){
      $order_str = " order by {$order}";
  }else{
      $order_str = '';
  }
  $sql = "select {$field_str} from `{$table}` {$where_str} {$order_str} {$limit_str}";
  $data = $db->query($sql);
  if ($data) {
    $posts = array();
    while($row = mysqli_fetch_assoc($data)) {
        $posts[] = $row;
    }
    unset($row);
    $data = $posts;
  }else{
    $data = array();
  }
  $db->close();
  return $data;
}
function count_sql($table,$where=false){
  require 'config.php';
  $db = new mysqli($config['database']['ip'],$config['database']['user'],$config['database']['password'],$config['database']['database'],$config['database']['port']);
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }
  $db->query("set names 'UTF8'");
  if ($where) {
    $where_str = "where {$where}";
  }else{
    $where_str = '';
  }
  $sql = "select count(*) as num from `{$table}` {$where_str}";
  $data = $db->query($sql);
  if ($data) {
    $posts = array();
    while($row = mysqli_fetch_assoc($data)) {
        $posts[] = $row;
    }
    unset($row);
    $data = $posts;
  }else{
    $data = array();
  }
  $db->close();
  return $data[0]['num'];
}
function insert($table,$data)
{
  require 'config.php';
  $db = new mysqli($config['database']['ip'],$config['database']['user'],$config['database']['password'],$config['database']['database'],$config['database']['port']);
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }
  $db->query("set names 'UTF8'");
  $col = '';
  $value = '';
  foreach ($data as $key => $val) {
    $col .= '`'.$key.'`,';
    $value .= '"'.$val.'",';
  }
  $col = substr($col,0,strlen($col)-1);
  $value = substr($value,0,strlen($value)-1);
  $sql = "insert into `{$table}`({$col}) values({$value})";
  $res = $db->query($sql);
  $db->close();
  if ($res) {
    return true;
  }else{
    return false;
  }
}
function debug($value2='',$value='')
{
  $dir = 'debug.txt';
  $fh = fopen($dir, "a");
  fwrite($fh, '系统时间：'.date('Y-m-d H:i:s',time())."\r\n");
  if ($value2) {
      if (is_array($value2)) {
          $value2 = print_r($value2,true);
      }
      fwrite($fh, $value2."\r\n");
  }
  if ($value) {
      if (is_array($value)) {
          $value = print_r($value,true);
      }
      fwrite($fh, $value."\r\n");
  }
  fclose($fh);
}