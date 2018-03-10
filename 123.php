<?php
    $caifenlv = 0.02;
    $subUser = 10;
    $land_level = 1;
    if ($subUser >= 30){
        $caifenlv = 0.035;
    }
    elseif ($subUser > 0){
        $caifenlv += floor($subUser/10)*0.005;
    }
    $caifenlv += ($land_level-1)*0.002;
    $rbamboo = round(round(63+50.7,1)*$caifenlv,1);
    echo(date("Y-m-d-h:i:sa",1496666833));
//    echo $caifenlv;
//    echo $rbamboo;

?>