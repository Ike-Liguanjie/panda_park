<?php
require 'function.php';
if (is_post()) {
    $username = isset($_POST['username'])?$_POST['username']:'';
    $password = isset($_POST['password'])?$_POST['password']:'';
    $password_rep = isset($_POST['password_rep'])?$_POST['password_rep']:'';
    $password2 = isset($_POST['password2'])?$_POST['password2']:'';
    $password2_rep = isset($_POST['password2_rep'])?$_POST['password2_rep']:'';
    $tuijianren = isset($_POST['tuijianren'])?$_POST['tuijianren']:'';
    $real_name = isset($_POST['real_name'])?$_POST['real_name']:'';
    $zfb = isset($_POST['zfb'])?$_POST['zfb']:'';
    $wx = isset($_POST['wx'])?$_POST['wx']:'';
	$phone = isset($_POST['phone'])?$_POST['phone']:'';
	$code = isset($_POST['code'])?$_POST['code']:'';
    $param_str = $username.$password.$password_rep.$password2.$password2_rep.$tuijianren.$real_name.$zfb.$wx.$phone.$code;
    if ($password != $password_rep) {
        echo json_encode(array('code'=>0,'msg'=>'两次密码不一样'));
        return;
    }
    if ($password2 != $password2_rep) {
        echo json_encode(array('code'=>0,'msg'=>'二级密码两次密码不一样'));
        return;
    }
    //检查是否有非法字符
    if (strpos($param_str,'"') != false) {
        echo json_encode(array('code'=>0,'msg'=>'请求参数包含非法参数，请确认'));
        return;
    }
    if (strpos($param_str,"'") != false) {
        echo json_encode(array('code'=>0,'msg'=>'请求参数包含非法参数，请确认'));
        return;
    }
    if (strpos($param_str,"/") != false) {
        echo json_encode(array('code'=>0,'msg'=>'请求参数包含非法参数，请确认'));
        return;
    }
    if (strpos($param_str,"\\") != false) {
        echo json_encode(array('code'=>0,'msg'=>'请求参数包含非法参数，请确认'));
        return;
    }
    if (strpos($param_str,",") != false) {
        echo json_encode(array('code'=>0,'msg'=>'请求参数包含非法参数，请确认'));
        return;
    }
    //判断用户名是否存在
    $data = select('user',"`username`='{$username}'");
    if ($data) {
        echo json_encode(array('code'=>0,'msg'=>'该用户名已经注册，请重新输入'));
        return;
    }
    //判断推荐人是否存在
    $parent = select('user',"`username`='{$tuijianren}'");
    if (!$parent) {
        echo json_encode(array('code'=>0,'msg'=>'推荐人不存在，请确认'));
        return;
    }
    //手机号是否已注册
    $phoneHas = select('user',"`phone`='{$phone}'");
    if ($phoneHas) {
        echo json_encode(array('code'=>0,'msg'=>'手机号已经注册过了'));
        return;
    }
    //验证手机号
    if ($phone != $_SESSION['code_phone']){
        echo json_encode(array('code'=>0,'msg'=>'验证码与手机不匹配'));
        return;
    }
    if ($code != $_SESSION['code']){
        echo json_encode(array('code'=>0,'msg'=>'验证码错误'));
        return;
    }
    if (time() > $_SESSION['code_get_time'] + 1800){
        echo json_encode(array('code'=>0,'msg'=>'验证码过期'));
        return;
    }
    //插入数据
    $inda['username'] = $username;
    $inda['real_name'] = $real_name;
    $inda['password'] = md5($password);
    $inda['password2'] = md5($password2);
    $inda['zfb'] = $zfb;
    $inda['wx'] = $wx;
	$inda['phone'] = $phone;
    $inda['parent_user_id'] = $parent[0]['id'];
    if (insert('user',$inda)) {
        echo json_encode(array('code'=>1,'msg'=>'注册成功'));
    }else{
        echo json_encode(array('code'=>0,'msg'=>'注册失败，请稍后再试'));
    }
}