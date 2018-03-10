<?php
//setcookie('is_login','',time()-86400*360);
//setcookie('username','',time()-86400*360);
//setcookie('user_id','',time()-86400*360);
session_start();
session_destroy();
header("Location: login.php");