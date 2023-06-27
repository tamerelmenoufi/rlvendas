<?php
error_reporting(0);
include "connection.php";
include "config.php";
include "utils.php";
include "fn.php";
include "vendor/rede/classes.php";
// include "AppWapp.php";
$md5 = md5(date("YmdHis"));


$query = "select * from atendentes where codigo = '{$_SESSION['AppGarcom']}'";
$Perfil = mysqli_fetch_object(mysqli_query($con, $query));