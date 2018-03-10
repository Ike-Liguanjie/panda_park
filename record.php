<?php
require_once './app/function.php';
require_once './app/config.php';
if(!is_login()){
    header("Location: login.php");
}
if(is_post()){
    $order = select('order_mod',"`id`={$_POST['id']} AND `seller_id`={$_SESSION['user_id']} AND `is_submit`=0");
    if ($order){
        $order = $order[0];
    }else{
        echo json_encode(array('code'=>0,'msg'=>'订单异常'));
        exit();
    }
    $db = new mysqli($config['database']['ip'],$config['database']['user'],$config['database']['password'],$config['database']['database'],$config['database']['port']);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    $db->query("set names 'UTF8'");
    $db->autocommit(false);//开启事务
    $res1 = $db->query("UPDATE `user` SET gold=gold+{$order['amount']} WHERE id={$order['get_user_id']}");//添加别人的
    $res2 = $db->query("UPDATE `order_mod` SET is_submit=1,submit_time='".date('Y-m-d H:i:s')."' WHERE id={$order['id']}");
    if ($res1 && $res2){
        $db->commit();
        echo json_encode(array('code'=>1,'msg'=>'操作成功'));
        exit();
    }else{
        echo json_encode(array('code'=>0,'msg'=>'操作失败，请稍后再试'));
        exit();
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>大象天堂-交易记录</title>
    <script src="js/jquery-2.1.4.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/GameControl.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>
<?php
require_once './app/function.php';
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

//获取交易信息
$page = isset($_GET['page'])?intval($_GET['page']):1;//当前第几页
$rows = 10;//每页显示的个数
$order_info = select('order_mod',"`seller_id`={$_SESSION['user_id']} OR `get_user_id`={$_SESSION['user_id']}",$field=false,($page-1)*$rows.','.$rows,"`create_time` desc");
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
    <div class="panel-heading">交易记录</div>
    <!-- Table -->
    <table class="table">
        <tr>
            <td class="text-center">卖方姓名</td>
            <td class="text-center">买方姓名</td>
            <td class="text-center">交易金额</td>
            <td class="text-center">交易状态</td>
            <td class="text-center">交易时间</td>
        </tr>
        <?php
            foreach ($order_info as $order){
                $status = "";
                if ($order['is_submit'] == 1){
                    $status = '已确认';
                }elseif ($order['get_user_id'] == $_SESSION['user_id']){
                    $status = '未确认';
                }elseif ($order['seller_id'] == $_SESSION['user_id']){
                    $status = "<a href='javascript:;' onclick='sub_order({$order['id']})'>未确认</a>";
                }

                $str = "";
                $str .= "<tr>";
                $str .= "<td class=\"text-center\">{$order['seller_real_name']}</td>";
                $str .= "<td class=\"text-center\">{$order['get_real_name']}</td>";
                $str .= "<td class=\"text-center\">{$order['amount']}</td>";
                $str .= "<td class=\"text-center\">$status</td>";
                $str .= "<td class=\"text-center\">{$order['create_time']}</td>";
                $str .= "</tr>";
                echo $str;
            }
        ?>
        <script>
            function sub_order(id) {
                $.post('record.php', {id:id}, function(data, textStatus, xhr) {
                    data = $.parseJSON(data);
                    location.reload();
                    alert(data['msg']);
                });
            }
        </script>
    </table>

</div>
    <nav aria-label="Page navigation" class="mytable2">
        <ul class="pagination">
            <li>
                <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="active"><a href="#">1</a></li>
            <li>
                <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</body>
</html>