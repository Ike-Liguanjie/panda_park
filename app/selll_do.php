<?php
require 'function.php';
if (is_post() && is_login()) {
  // $gold = intval($_POST['gold']);
  $gold = intval($_POST['gold']);
  if (!$gold) {
    echo json_encode(array('code'=>0,'msg'=>'参数错误，操作失败'));
    return;
  }
  //判断金额时候足够
  $userinfo = select('user',"id='{$_SESSION['user_id']}'");
  $userinfo = $userinfo[0];
  if ($gold > $userinfo['gold']){
      echo json_encode(array('code'=>0,'msg'=>'金额不够转出'.$gold));
      return;
  }
    require 'config.php';
    $db = new mysqli($config['database']['ip'],$config['database']['user'],$config['database']['password'],$config['database']['database'],$config['database']['port']);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    $db->query("set names 'UTF8'");
    $db->autocommit(false);//开启事务
    $res1 = $db->query("UPDATE `user` SET gold=gold-{$gold} WHERE id={$_SESSION['user_id']}");//减少自己的
    $time = time();
    $res2 = $db->query("INSERT INTO `order_selll`(`seller_id`,`seller_username`,`gold`,`create_time`) VALUES({$_SESSION['user_id']},'{$_SESSION['username']}',{$gold},{$time})");//向表中添加
    if(!$db->errno){
        if ($res1 && $res2){
            $db->commit();
            echo json_encode(array('code'=>0,'msg'=>'操作成功'));
        }else{
            $db->rollback();
            echo json_encode(array('code'=>0,'msg'=>'操作失败,请稍后再试'));
        }
    }else{
        $db->rollback();
        echo json_encode(array('code'=>0,'msg'=>'操作失败,请稍后再试'));
    }
    $db->close();


}