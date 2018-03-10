<?php 
    require './app/function.php';
?>
<?php
    $id=intval($_GET['id']);
    $user_info = select('user',"id=".$id,"username");
    $user_info = $user_info[0];//用户信息
    if($user_info){
        $lead_name = $user_info['username'];
    }else{
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>大象天堂-注册</title>
    <script src="js/jquery-2.1.4.js"></script>
    <script src="js/bootstrap.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-default shadow">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">大象天堂</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">会员管理<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="register.php?id=<?php echo $_SESSION['user_id']; ?>">注册</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">交易中心<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="sell.php">出售大象币</a></li>
                        <li><a href="record.php">交易记录</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">个人中心<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="perinfo.php">个人信息</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a>欢迎您，</a></li><li><a href="logout.php">退出登录</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<!-- 导航栏-->
<div class="register">
    <div class="panel panel-primary register">
        <!-- Default panel contents -->
        <div class="panel-heading">注册</div>
        <form id="register_form" class="form-horizontal top">
            <div class="form-group">
                <label for="inputUserID" class="col-sm-4 control-label">会员账户：</label>
                <div class="col-sm-4">
                    <input name="username" type="text" class="form-control" id="inputUserID" placeholder="请输入字母+数字">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="col-sm-4 control-label">密码：</label>
                <div class="col-sm-4">
                    <input name="password" type="password" class="form-control" id="inputPassword">
                </div>
            </div>
            <div class="form-group">
                <label for="checkPassword" class="col-sm-4 control-label">确认密码：</label>
                <div class="col-sm-4">
                    <input name="password_rep" type="password" class="form-control" id="checkPassword">
                </div>
            </div>
            <div class="form-group">
                <label for="inputScondPd" class="col-sm-4 control-label">二级密码：</label>
                <div class="col-sm-4">
                    <input name="password2" type="password" class="form-control" id="inputScondPd" placeholder="建议6位密码">
                </div>
            </div>
            <div class="form-group">
                <label for="checkScondPd" class="col-sm-4 control-label">确认二级密码：</label>
                <div class="col-sm-4">
                    <input name="password2_rep" type="password" class="form-control" id="checkScondPd">
                </div>
            </div>
            <div class="form-group">
                <label for="inputLeaderID" class="col-sm-4 control-label">推荐人：</label>
                <div class="col-sm-4">
                    <input name="tuijianren" type="text" class="form-control" id="inputLeaderID" value="<?php echo $lead_name; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="inputUserName" class="col-sm-4 control-label">姓名：</label>
                <div class="col-sm-4">
                    <input name="real_name" type="text" class="form-control" id="inputUserName">请输入真实姓名，以方便打款
                </div>
            </div>
            <div class="form-group">
                <label for="inputphone" class="col-sm-4 control-label">手机号：</label>
                <div class="col-sm-4">
                    <input name="phone" type="text" class="form-control" id="inputphone" >
                    <a onclick="get_code()">获取验证码</a>
                </div>
            </div>
            <div class="form-group">
                <label for="inputcode" class="col-sm-4 control-label">验证码：</label>
                <div class="col-sm-4">
                    <input name="code" type="text" class="form-control" id="inputcode" >
                </div>
            </div>
            <div class="form-group">
                <label for="inputAlipay" class="col-sm-4 control-label">支付宝：</label>
                <div class="col-sm-4">
                    <input name="zfb" type="text" class="form-control" id="inputAlipay" >
                </div>
            </div>
            <div class="form-group">
                <label for="inputWechat" class="col-sm-4 control-label">微信：</label>
                <div class="col-sm-4">
                    <input name="wx" type="text" class="form-control" id="inputWechat">
                </div>
            </div>
            <div class="form-group" style="display: none;" id="notice_message">
                <div class="col-sm-offset-5 col-sm-10">
                    <a class="btn btn-warning"></a>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-5 col-sm-10">
                    <a onclick="register();" class="btn btn-primary">提交信息</a>
                </div>
                <script>
                    function register(){
                        $('#notice_message').hide();
                        var username = $('input[name="username"]').val();
                        var password = $('input[name="password"]').val();
                        var password_rep = $('input[name="password_rep"]').val();
                        var password2 = $('input[name="password2"]').val();
                        var password2_rep = $('input[name="password2_rep"]').val();
                        var tuijianren = $('input[name="tuijianren"]').val();
                        var real_name = $('input[name="real_name"]').val();
                        var phone = $('input[name="phone"]').val();
                        var zfb = $('input[name="zfb"]').val();
                        var wx = $('input[name="wx"]').val();
                        var code = $('input[name="code"]').val();
                        var notice = '';
                        if (!wx) {
                            notice = '请输入微信账号';
                        }
                        if (!zfb) {
                            notice = '请输入支付宝账号';
                        }
                        if (!phone) {
                            notice = '请输入手机号码';
                        }
                        if (!real_name) {
                            notice = '请输入真实姓名';
                        }
                        if (!tuijianren) {
                            notice = '请输入推荐人';
                        }
                        if (password2 != password2_rep) {
                            notice = '二级密码与确认二级密码不相同';
                        }
                        if (!password2_rep) {
                            notice = '请输入确认二级密码';
                        }
                        if (!password2) {
                            notice = '请输入二级密码';
                        }
                        if (password != password_rep) {
                            notice = '确认密码与密码不相同';
                        }
                        if (!password_rep) {
                            notice = '请输入确认密码';
                        }
                        if (!password) {
                            notice = '请输入密码';
                        }
                        if (!username) {
                            notice = '请输入用户名';
                        }
                        if (!code) {
                            notice = '请输入验证码';
                        }
                        if (notice != '') {
                            $('#notice_message').show();
                            $('#notice_message').find('a').html(notice);
                            return false;
                        }
                        $.post('./app/register_do.php', $('#register_form').serialize(), function(data, textStatus, xhr) {
                            data = $.parseJSON(data);
                            if (data['code'] == 0) {
                                $('#notice_message').show();
                                $('#notice_message').find('a').html(data['msg']);
                                return false;
                            }else{
                                //注册成功
                                $('#notice_message').show();
                                $('#notice_message').find('a').html(data['msg']);
                            }
                        });
                    }
                    function get_code() {
                        var phone = $('input[name="phone"]').val();
                        $.post('./app/mns_send.php', {phone:phone}, function(data, textStatus, xhr) {
                            data = $.parseJSON(data);
                            if (data['code'] == 0) {
                                $('#notice_message').show();
                                $('#notice_message').find('a').html(data['msg']);
                                return false;
                            }else{
                                //注册成功
                                $('#notice_message').show();
                                $('#notice_message').find('a').html(data['msg']);
                            }
                        });
                    }
                </script>
            </div>
        </form>
    </div>
</div>
</body>
</html>