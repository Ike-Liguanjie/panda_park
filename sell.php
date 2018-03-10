<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>大象天堂-指定出售</title>
    <script src="js/jquery-2.1.4.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/GameControl.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>
<?php 
    require './app/function.php';
?>
<?php
    if(!is_login()){
        header("Location: login.php"); 
    }
    //查询会员信息
    $user_info = select('user',"id=".$_SESSION['user_id']);
    $user_info = $user_info[0];
    if(!is_checkSPD()){
        header("Location: checkScondPd.php");
    }
?>
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
                <li><a>欢迎您，<?php echo $_SESSION['username']; ?></a></li><li><a href="logout.php">退出登录</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<!-- 导航栏-->
<div class="sell shadow">
    <ul class="nav nav-tabs nav-justified">
        <li role="presentation" class="active"><a href="sell.php">指定出售</a></li>
        <li role="presentation"><a href="selll.php">委托出售</a></li>
    </ul>
    <div class="sell-1">
        <form class="form-horizontal" id="sell_form">
            <div class="form-group">
                <label for="inputUserID" class="col-sm-4 control-label">转入会员账号：</label>
                <div class="col-sm-6">
                    <input type="email" name="username" class="form-control" id="inputUserID">
                </div>
            </div>
            <div class="form-group">
                <label for="inputUsername" class="col-sm-4 control-label">转入会员姓名：</label>
                <div class="col-sm-6">
                    <input type="text" name="real_name" class="form-control" id="inputUsername">
                </div>
            </div>
            <div class="form-group">
                <label for="inputGoldnum" class="col-sm-4 control-label">转出金额：</label>
                <div class="col-sm-6">
                    <input type="text" name="gold" class="form-control" id="inputGoldnum">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">会员姓名：<?php echo $user_info['username']; ?></label>
                <label class="col-sm-3 control-label">余额：<?php echo $user_info['gold']; ?></label>
            </div>
            <div class="form-group">
                <label class="col-sm-10 control-label" style="color: red">交易需扣除金额的10%作为手续费，请保证余额充足。</label>
            </div>
            <div class="form-group">
                <label class="col-sm-11 control-label">温馨提示：请确认信息无误之后再进行转出，否则造成的一切损失由自己负责。</label>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-5 col-sm-10">
                    <a class="btn btn-info" onclick="sell();">　　确认转出　　</a>
<script>
    function sell(){
        var username = $('#sell_form').find('input[name="username"]').val();
        var real_name = $('#sell_form').find('input[name="real_name"]').val();
        var gold = $('#sell_form').find('input[name="gold"]').val();
        $.post('./app/sell_do.php', {username:username,real_name:real_name,gold:gold}, function(data, textStatus, xhr) {
            data = $.parseJSON(data);
            alert(data['msg']);
        });
    }
</script>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>