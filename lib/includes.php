<?php
error_reporting(0);
include "connection.php";
include "config.php";
include "utils.php";
include "fn.php";
include "vendor/rede/classes.php";
// include "AppWapp.php";
$md5 = md5(date("YmdHis"));


if($_SERVER['HTTP_HOST'] == 'app.yobom.com.br'){
    header("location:https://yobom.com.br/rlvendas/app/");
    exit();
}

$query = "select * from atendentes where codigo = '{$_SESSION['AppGarcom']}'";
$Perfil = mysqli_fetch_object(mysqli_query($con, $query));