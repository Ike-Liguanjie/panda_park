<?php
require 'function.php';
$user_info = select('user',"id=".$_SESSION['user_id'],"gold,bamboo,land_level");
$user_info = $user_info[0];//用户信息
$a = $user_info['land_level'];
$land_level = $user_info['land_level'];
/*游戏配置*/
require 'game_config.php';
$land_max_level = count($level)/2;
$open_reduce = $level[2*$a - 2];//开发所需消耗
$land_max_weight = $level[2*$a - 1];//复投和喂养最大重量
/*游戏配置*/

if (is_login() && is_post()){
    require 'config.php';
    $db = new mysqli($config['database']['ip'],$config['database']['user'],$config['database']['password'],$config['database']['database'],$config['database']['port']);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    $db->query("set names 'UTF8'");
    $db->autocommit(false);//开启事务
    if (isset($_POST['open']) && $_POST['open'] == 1){
        /**
         * 开发土地
         */
        if (!isset($_POST['land_id'])){
            echo json_encode(array('code'=>0,'msg'=>'参数异常,操作失败'));
            $db->close();
            return;
        }
        $land_id = intval($_POST['land_id']);
        $userinfo = select('user',"id='{$_SESSION['user_id']}'");
        $userinfo = $userinfo[0];
        if ($userinfo['gold']<$open_reduce){
            echo json_encode(array('code'=>0,'msg'=>'所需消耗不足'));
            $db->close();
            return;
        }
        $has_open = select('user_land',"user_id={$_SESSION['user_id']} AND land_id={$land_id} AND status=1");
        if ($has_open){
            echo json_encode(array('code'=>0,'msg'=>'这块土地已经开发过了'));
            $db->close();
            return;
        }
        $res1 = $db->query("UPDATE `user` SET gold=gold-{$open_reduce} WHERE id={$_SESSION['user_id']}");
        $res2 = $db->query("INSERT INTO `user_land`(`user_id`,`land_id`,`status`,`land_weight`) VALUES({$_SESSION['user_id']},{$land_id},1,{$open_reduce})");
        if ($res1 && $res2){
            $db->commit();
            $db->close();
            echo json_encode(array('code'=>1));
            return;
        }else{
            echo json_encode(array('code'=>0,'msg'=>'操作失败，请稍后再试'));
            $db->close();
            return;
        }
    }elseif (isset($_POST['futou']) && $_POST['futou'] == 1){
        /**
         * 复投
         */
        if (!isset($_POST['land_id']) || !isset($_POST['tou_num'])){
            echo json_encode(array('code'=>0,'msg'=>'参数异常,操作失败'));
            $db->close();
            return;
        }
        $land_id = intval($_POST['land_id']);
        // $tou_num = intval($_POST['tou_num']);
        $tou_num = round($_POST['tou_num'],2);
        if ($tou_num <= 0){
            echo json_encode(array('code'=>0,'msg'=>'参数异常,操作失败'));
            $db->close();
            return;
        }
        $userinfo = select('user',"id='{$_SESSION['user_id']}'");
        $userinfo = $userinfo[0];
        if ($userinfo['gold']<$tou_num){
            echo json_encode(array('code'=>0,'msg'=>'所需消耗不足'));
            $db->close();
            return;
        }
        $land_info = select('user_land',"user_id={$_SESSION['user_id']} AND land_id={$land_id} AND status=1");
        if (!$land_info){
            echo json_encode(array('code'=>0,'msg'=>'这块土地还未开发过了'));
            $db->close();
            return;
        }
        $land_info = $land_info[0];
        if ($land_info['land_weight']+$tou_num > $land_max_weight){
            echo json_encode(array('code'=>0,'msg'=>'超出土地上限'));
            $db->close();
            return;
        }
        $res1 = $db->query("UPDATE `user` SET gold={$user_info['gold']} - {$tou_num} WHERE id={$_SESSION['user_id']}");
        $res2 = $db->query("UPDATE `user_land` SET land_weight=land_weight+{$tou_num} WHERE user_id={$_SESSION['user_id']} AND `land_id`={$land_id}");
        if ($res1 && $res2){
            $db->commit();
            $db->close();
            echo json_encode(array('code'=>1));
            return;
        }else{
            echo json_encode(array('code'=>0,'msg'=>'操作失败，请稍后再试'));
            $db->close();
            return;
        }
    }elseif (isset($_POST['weishi']) && $_POST['weishi'] == 1){
        /**
         * 喂食
         */
        if (!isset($_POST['land_id']) || !isset($_POST['tou_num'])){
            echo json_encode(array('code'=>0,'msg'=>'参数异常,操作失败'));
            $db->close();
            return;
        }
        $land_id = intval($_POST['land_id']);
        // $tou_num = intval($_POST['tou_num']);
        $tou_num = round($_POST['tou_num'],2);
        if ($tou_num <= 0){
            echo json_encode(array('code'=>0,'msg'=>'参数异常,操作失败'));
            $db->close();
            return;
        }
        $userinfo = select('user',"id='{$_SESSION['user_id']}'");
        $userinfo = $userinfo[0];
        if ($userinfo['bamboo']<$tou_num){
            echo json_encode(array('code'=>0,'msg'=>'所需消耗不足'));
            $db->close();
            return;
        }
        $land_info = select('user_land',"user_id={$_SESSION['user_id']} AND land_id={$land_id} AND status=1");
        if (!$land_info){
            echo json_encode(array('code'=>0,'msg'=>'这块土地还未开发过了'));
            $db->close();
            return;
        }
        $land_info = $land_info[0];
        if ($land_info['land_weight']+$tou_num > $land_max_weight){
            echo json_encode(array('code'=>0,'msg'=>'超出土地上限'));
            $db->close();
            return;
        }
        $res1 = $db->query("UPDATE `user` SET bamboo=bamboo-{$tou_num} WHERE id={$_SESSION['user_id']}");
        $res2 = $db->query("UPDATE `user_land` SET land_weight=land_weight+{$tou_num} WHERE user_id={$_SESSION['user_id']} AND `land_id`={$land_id}");
        if ($res1 && $res2){
            $db->commit();
            $db->close();
            echo json_encode(array('code'=>1));
            return;
        }else{
            echo json_encode(array('code'=>0,'msg'=>'操作失败，请稍后再试'));
            $db->close();
            return;
        }
    }elseif (isset($_POST['shouqu']) && $_POST['shouqu'] == 1){
        /**
         * 收取
         */
        if (!isset($_POST['land_id'])){
            echo json_encode(array('code'=>0,'msg'=>'参数异常,操作失败'));
            $db->close();
            return;
        }
        $land_id = intval($_POST['land_id']);
        $land_info = select('user_land',"user_id={$_SESSION['user_id']} AND land_id={$land_id} AND status=1");
        if (!$land_info){
            echo json_encode(array('code'=>0,'msg'=>'这块土地还未开发过了'));
            $db->close();
            return;
        }
        $land_info = $land_info[0];
        if ($land_info['land_weight'] <= $open_reduce){
            echo json_encode(array('code'=>0,'msg'=>'土地不能收取'));
            $db->close();
            return;
        }
        $shouqu = round($land_info['land_weight'] - $open_reduce,2);
        $res1 = $db->query("UPDATE `user` SET gold=gold+{$shouqu} WHERE id={$_SESSION['user_id']}");
        $res2 = $db->query("UPDATE `user_land` SET land_weight={$open_reduce} WHERE user_id={$_SESSION['user_id']} AND `land_id`={$land_id}");
        if ($res1 && $res2){
            $db->commit();
            $db->close();
            echo json_encode(array('code'=>1));
            return;
        }else{
            echo json_encode(array('code'=>0,'msg'=>'操作失败，请稍后再试'));
            $db->close();
            return;
        }
    }elseif (isset($_POST['up_level']) && $_POST['up_level'] == 1) {
        /**
         * 升级
         */
        //判断升级条件
        $land_num = count_sql('user_land',"user_id={$_SESSION['user_id']} AND status=1");
        if ($land_num == $land_max_num){
            //判断当前是否是最大等级
            if ($land_level >= $land_max_weight){
                echo json_encode(array('code'=>0,'msg'=>'操作失败，土地等级已经是最大'));
                $db->close();
                return;
            }
            //升级操作
            $data = $db->query("select SUM(`land_weight`) as num from `user_land` where user_id={$_SESSION['user_id']} AND status=1");
            if ($data) {
                $posts = array();
                while($row = mysqli_fetch_assoc($data)) {
                    $posts[] = $row;
                }
                unset($row);
                $data = $posts;
            }else{
                $data = array();
            }
            $add_gold = $data[0]['num']-$land_max_num*$open_reduce;
            if ($add_gold>0){
                $res7 = $db->query("UPDATE `user` SET gold=gold+{$add_gold} WHERE id={$_SESSION['user_id']}");
            }else{
                $res7 = 1;
            }
            $res1 = $db->query("UPDATE `user` SET land_level=land_level+1 WHERE id={$_SESSION['user_id']}");
            $res2 = $db->query("DELETE FROM `user_land` WHERE user_id={$_SESSION['user_id']} AND status=1");
            $open_reduce = $level[2*($a+1) - 2];//开发所需消耗
            $res3 = $db->query("INSERT INTO `user_land`(`user_id`,`land_id`,`status`,`land_weight`) VALUES({$_SESSION['user_id']},1,1,{$open_reduce})");
            $res4 = $db->query("INSERT INTO `user_land`(`user_id`,`land_id`,`status`,`land_weight`) VALUES({$_SESSION['user_id']},2,1,{$open_reduce})");
            $res5 = $db->query("INSERT INTO `user_land`(`user_id`,`land_id`,`status`,`land_weight`) VALUES({$_SESSION['user_id']},3,1,{$open_reduce})");
            $res6 = $db->query("INSERT INTO `user_land`(`user_id`,`land_id`,`status`,`land_weight`) VALUES({$_SESSION['user_id']},4,1,{$open_reduce})");
            if ($res1 && $res2 && $res3 && $res4 && $res5 && $res6 && $res7){
                $db->commit();
                $db->close();
                echo json_encode(array('code'=>1));
                return;
            }else{
                echo json_encode(array('code'=>0,'msg'=>'操作失败，请稍后再试'));
                $db->close();
                return;
            }

        }else{
            echo json_encode(array('code'=>0,'msg'=>'操作失败，土地不满足升级条件'));
            $db->close();
            return;
        }
    }
}
