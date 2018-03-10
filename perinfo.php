<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>大象天堂-个人信息</title>
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
        //推荐人信息
        $parent = select('user','id='.$user_info['parent_user_id']);
        @$parent = $parent[0];
        //团队总人数
        $total_num = count_sql('user','parent_user_id='.$user_info['id']);
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
    <!--<div class="perinfo shadow">-->
        <div class="panel panel-primary mytable">
            <!-- Default panel contents -->
            <div class="panel-heading">个人信息</div>
            <!-- Table -->
            <table class="table">
                <tr>
                    <td class="text-right">会员账号：</td>
                    <td><?php echo $user_info['username']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">会员姓名：</td>
                    <td><?php echo $user_info['real_name']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">手机号码：</td>
                    <td><?php echo $user_info['phone']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">支付宝：</td>
                    <td><?php echo $user_info['zfb']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">微信号：</td>
                    <td><?php echo $user_info['wx']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">团队总人数：</td>
                    <td><?php echo $total_num; ?></td>
                </tr>
                <tr>
                    <td class="text-right">推荐人账号：</td>
                    <td><?php echo $parent['username']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">推荐人姓名：</td>
                    <td><?php echo $parent['real_name']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">推荐人微信：</td>
                    <td><?php echo $parent['wx']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">推荐人支付宝：</td>
                    <td><?php echo $parent['zfb']; ?></td>
                </tr>
                <tr>
                    <td class="text-right">推广链接：</td>
                    <td>www.huags.cn/register.php?id=<?php echo $_SESSION['user_id']; ?></td>
                </tr>
            </table>
        </div>
</body>
</html>