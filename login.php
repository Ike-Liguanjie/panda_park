<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>大象天堂-登录</title>
    <script src="js/jquery-2.1.4.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/pub.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>
<?php 
    require './app/function.php';
?>
<?php
    $notice = false;
    if (is_post()) {
        $username = isset($_POST['username'])?$_POST['username']:'';
        $password = isset($_POST['password'])?$_POST['password']:'';
        $checknum = isset($_POST['checknum'])?$_POST['checknum']:'';

        if ($username && $password) {
            //登录流程
            $user_info = select('user',"username='{$username}'");
            if ($user_info) {
                $user_info = $user_info[0];
                if (md5($password) == $user_info['password']) {
                    //登录成功
                    //setcookie('is_login',1,time()+86400*30);//保留登录状态30天
                    //setcookie('username',$user_info['username'],time()+86400*30);
                    //setcookie('user_id',$user_info['id'],time()+86400*30);

                    $_SESSION['is_login'] = 1;
                    $_SESSION['username'] = $user_info['username'];
                    $_SESSION['user_id'] = $user_info['id'];

                    //返还竹子逻辑最近一次登录不是在今天就执行
                    $loginInfo = select('login_log',"user_id={$user_info['id']}",false,1,"login_time desc");
                    $return = false;
                    if ($loginInfo){
                        if (date('Y-m-d',$loginInfo[0]['login_time']) != date('Y-m-d',time())){
                            $return = true;
                        }
                    }else{
                        $return = true;
                    }
                    //返回竹子
                    if ($return){
                        $father_info = select('user','id='.$user_info['parent_user_id']);
                        $father_info = $father_info[0];
                        $grand_info = select('user','id='.$father_info['parent_user_id']);
                        $grand_info = $grand_info[0];
                        $rbamboo = 0;//应该返回的竹子数量
                        $tizhong = 0;//体重
                        $cangku = $user_info['gold'];
                        $land_level = $user_info['land_level'];
                        //土地信息
                        $land_info = select('user_land',"status=1 AND user_id=".$user_info['id'],"land_id,land_weight");
                        if (!$land_info){
                            $land_info = array();
                        }
                        foreach ($land_info as $val){
                            $tizhong += $val['land_weight'];
                        }

                        $caifenlv = 0.02;//拆分率
                        //计算下线人数
                        $subUser = count_sql('user',"parent_user_id={$user_info['id']}");
                        if ($subUser >= 30){
                            $caifenlv = 0.035;
                        }
                        elseif ($subUser > 0){
                            $caifenlv += floor($subUser/10)*0.005;
                        }
                        $caifenlv += ($land_level-1)*0.002;
                        $rbamboo = round(intval($tizhong)*$caifenlv,2);
                        $f_rbamboo = intval($rbamboo)/20;
                        $g_rbamboo = intval($rbamboo)/20;
                        if ($rbamboo > $user_info['bamboo']){
                            require './app/config.php';
                            $db = new mysqli($config['database']['ip'],$config['database']['user'],$config['database']['password'],$config['database']['database'],$config['database']['port']);
                            if ($db->connect_error) {
                                die("Connection failed: " . $db->connect_error);
                            }
                            $db->query("set names 'UTF8'");
                            $res = $db->query("UPDATE `user` SET bamboo={$rbamboo} WHERE id={$user_info['id']}");
                            $res2 = $db->query("UPDATE `user` SET bamboo=bamboo+{$f_rbamboo} WHERE id={$father_info['id']}");
                        }
                    }
                    //插入登录日志
                    $inda['user_id'] = $user_info['id'];
                    $inda['login_time'] = time();
                    $inda['login_ip'] = $_SERVER["REMOTE_ADDR"];
                    insert('login_log',$inda);

                    header("Location: index.php"); 
                }else{
                   $notice = '用户名或密码错误'; 
                }
            }else{
                $notice = '用户名或密码错误';
            }
        }else{
            $notice = "用户名或密码错误";
        }
    }
?>
<div class="login">
    <div style="margin: auto;width: 200px;height: 148px;padding-top: 10%;" >
        <img src="picture/logo.png">
    </div>
    <div class="box">
        <div class="content">
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label for="inputUserName" class="col-sm-2 control-label sr-only">Email：</label>
                    <div class="col-sm-8 input-group">
                        <div class="input-group-addon">用户名：</div>
                        <input name="username" type="text" class="form-control" id="inputUserName" placeholder="请输入用户名">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-sm-2 control-label sr-only">Password：</label>
                    <div class="col-sm-8 input-group">
                        <div class="input-group-addon">密　码：</div>
                        <input name="password" type="password" class="form-control" id="inputPassword" placeholder="请输入密码">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCheckNum" class="col-sm-2 control-label sr-only">Password：</label>
                    <div class=" col-sm-5 input-group">
                        <div class="input-group-addon">验证码：</div>
                        <input name="checknum" type="text" class="form-control" id="inputCheckNum" placeholder="请输入验证码">
                    </div>
                    <a href="#"><img class="float" src="picture/checknum.png"></a>
                </div>
                <div class="form-group" style="display: <?php if($notice){echo 'normal;';}else{echo 'none;';} ?>">
                    <div class="col-sm-offset-4 col-sm-10">
                        <a class="btn btn-warning"><?php echo $notice;?></a>
                    </div>
                </div>
                <!--<div class="float">-->
                    <!--<img src="picture/checknum.png">-->
                <!--</div>-->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input name="save_info" type="checkbox" checked="checked"> 保存信息
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-10">
                        <button type="submit" class="btn btn-info" onclick="login_befre();">　　登录　　</button>
                    </div>
                </div>

            </form>
            <script>
                function login_befre(){
                    var username = $('input[name="username"]').val();
                    var password = $('input[name="password"]').val();
                    createCookie('client_save_username',username,1000);
                    createCookie('client_save_password',password,1000);
                }
                $('input[name="username"]').val(readCookie('client_save_username'));
                $('input[name="password"]').val(readCookie('client_save_password'));
            </script>
        </div>
    </div>
</div>
</body>
</html>