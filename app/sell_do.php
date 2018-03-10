<?php
require 'function.php';
if (is_post() && is_login()) {
  $username = $_POST['username'];
  $real_name = $_POST['real_name'];
  // $gold = intval($_POST['gold']);
  $gold = intval($_POST['gold']);
  if (!$username || !$real_name || !$gold) {
    echo json_encode(array('code'=>0,'msg'=>'参数错误，操作失败'));
    return;
  }
  //判断要转出的用户名是否存在
  $get_user = select('user',"username='{$username}' AND real_name='{$real_name}'");
  if ($get_user) {
    $get_user = $get_user[0];
  }else{
    echo json_encode(array('code'=>0,'msg'=>'转出人不存在，请确认信息是否填写正确'));
    return;
  }
  //判断金额时候足够
  $userinfo = select('user',"id='{$_SESSION['user_id']}'");
  $userinfo = $userinfo[0];
    if($gold < 100){
        echo json_encode(array('code'=>0,'msg'=>'转出金额必须大于100!'));
        return;
    }elseif($gold+$gold/10 > $userinfo['gold']){
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
    $reduce_gold = $gold+$gold/10;
    $res1 = $db->query("UPDATE `user` SET gold=gold-{$reduce_gold} WHERE id={$_SESSION['user_id']}");//减少自己的
//    $res2 = $db->query("UPDATE `user` SET gold=gold+{$gold} WHERE id={$get_user['id']}");//添加别人的
    //添加记录表
    $inda = array();
    $inda['seller_id'] = $_SESSION['user_id'];
    $inda['seller_real_name'] = $userinfo['real_name'];
    $inda['get_user_id'] = $get_user['id'];
    $inda['get_real_name'] = $real_name;
    $inda['is_submit'] = 0;
    $inda['amount'] = $gold;
    $inda['create_time'] = date('Y-m-d H:i:s');
    $res2 = insert('order_mod',$inda);
    if(!$db->errno){
        if ($res1 && $res2){
            $db->commit();
            echo json_encode(array('code'=>0,'msg'=>'操作成功'));
            //存交易表
//            $inda['seller_id'] = $_SESSION['user_id'];
//            $inda['get_username'] = $username;
//            $inda['get_real_name'] = $real_name;
//            $inda['gold'] = $gold;
//            $inda['tax'] = $gold/10;
//            $inda['create_time'] = time();
//            insert('order',$inda);
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