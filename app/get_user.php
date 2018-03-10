<?php
require 'function.php';
if (is_login() && is_post()){
    if ($_POST['get_user'] == 1){
        //查询会员信息
        $user_info = select('user',"id=".$_SESSION['user_id'],"gold,bamboo,land_level,id");
        $user_info = $user_info[0];//用户信息
        //土地信息
        $land_info = select('user_land',"status=1 AND user_id=".$_SESSION['user_id'],"land_id,land_weight");
        if (!$land_info){
            $land_info = array();
        }
        $weight = 0;
        foreach ($land_info as $val){
            $weight += $val['land_weight'];
        }
        $user_info['weight'] = $weight;
        $user_info['land'] = $land_info;
        $user_info['subUser'] = count_sql('user',"parent_user_id={$user_info['id']}");
        /*游戏配置*/
        $a = $user_info['land_level'];
        require 'game_config.php';
        $open_reduce = $level[2*$a - 2];//开发所需消耗
        $land_max_weight = $level[2*$a - 1];//复投和喂养最大重量
        /*游戏配置*/
        $user_info['open_reduce'] = $open_reduce;
        $user_info['land_max_weight'] = $land_max_weight;
        echo json_encode($user_info);
        return;
    }
}