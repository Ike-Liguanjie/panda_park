<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>花果山-登录</title>
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
    if(is_post()){
        $password2 = isset($_POST['password2'])?$_POST['password2']:'';
        if($password2){
            $user_info = select('user',"id=".$_SESSION['user_id']);
            if($user_info){
                $user_info = $user_info[0];
                if (md5($password2) == $user_info['password2']){
                    $_SESSION['is_checkSPD'] = 1;
                    header("Location: index.php");
                }else{
                    $notice = '密码错误';
                }
            }else{
                $notice = '密码不存在';
            }
        }else{
            $notice = '请输入二级密码!';
        }
    }
?>
<form method="post">
    <div class="panel panel-primary checkSPD">
        <div class="panel-heading">请输入二级密码：</div>
        <td><input type="password" name="password2" class="form-control secondpd" ></td>
        <span class="label label-danger message" id="message"><?php echo $notice; ?></span>
        <div class="btn-toolbar yesorno" role="toolbar" aria-label="...">
            <div class="btn-group" role="group" aria-label="...">
                <button type="submit" class="btn btn-primary">确定</button>
            </div>
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">返回</button>
            </div>
        </div>
    </div>
</form>

</body>