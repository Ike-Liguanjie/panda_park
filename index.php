<!-- 判断登陆状态 -->
<?php
require './app/function.php';
?>
<?php
if(!is_login()){
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>大象天堂</title>
    <script src="js/jquery-2.1.4.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/GameControl.js"></script>
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
                    <li><a>欢迎您，<?php echo $_SESSION['username']; ?></a></li><li><a href="logout.php">退出登录</a></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <!-- 导航栏-->
    <div class="bgp shadow">
        <div class="information"></div>
        <div class="information2">
            <span>总量:</span>
            <span id="showzong"></span>
            <span>　　体重:</span>
            <span id="showtou"></span>
            <span>　　仓库:</span>
            <span id="showcang"></span>
            <span>　　树叶:</span>
            <span id="showliao"></span>
<!--            <span>　　偷崽:</span>-->
<!--            <span id="showjiang"></span>-->
            <span>　　饲养员:</span>
            <span id="showyuan"></span>
        </div>
        <div id="lands">
            <div class="land" data-toggle="modal" >
                <div class="num"><span>0</span></div>
                <!--<img class="num" src="picture/ani_6.png">-->
            </div>
        </div>
        <div class="tool2"></div>
        <div class="tool">
            <a href="#" onclick="Kaifa()"><img src="picture/tool_1.png"></a>
            <a href="#" onclick="Futou()"><img src="picture/tool_2.png"></a>
            <a href="#" onclick="Weiyang()"><img src="picture/tool_3.png"></a>
            <a href="#" onclick="Shouqu()"><img src="picture/tool_4.png"></a>
            <a href="#" onclick="Shengji()"><img src="picture/tool_5.png"></a>
        </div>

        <div id="build"><img src="picture/build.png"></div>



        <!-- Button trigger modal -->
        <!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">-->
        <!--Launch demo modal-->
        <!--</button>-->

        <!-- Modal -->
        <div class="modal fade bs-example-modal-sm" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel1">复投</h4>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">输入复投数量</span>
                            <input type="text" id="changetou" class="form-control"  aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="Changetou()">确认</button>
                        <!--<button type="button" class="btn btn-default">取消</button>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bs-example-modal-sm" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel2">喂养</h4>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon2">输入喂养数量</span>
                            <input type="text" id="changewei" class="form-control"  aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="Changewei()">确认</button>
                        <!--<button type="button" class="btn btn-default">取消</button>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>