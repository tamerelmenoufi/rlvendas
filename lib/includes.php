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
    foreach($_GET as $i => $v){
        $d = $i;
    }
    if(strlen($d) == 32){
        header("location:https://yobom.com.br/rlvendas/app/?{$d}");
        exit();
    }
}

if($_SERVER['HTTP_HOST'] == 'yobom.com.br'){

    foreach($_GET as $i => $v){
        $d = $i;
    }

echo $d; exit();

    // if(strlen($d)){
    //     $query = "select * from mesas where md5(mesa) = '{$d}' and situacao = '1' and deletado != '1'";
    //     $mesa = mysqli_fetch_object(mysqli_query($con, $query));  
    //     $_SESSION['AppPedido'] = $mesa->codigo;
    //     header("location:https://yobom.com.br/rlvendas/app/?n=1");
    //     exit();
    // }

}


$query = "select * from atendentes where codigo = '{$_SESSION['AppGarcom']}'";
$Perfil = mysqli_fetch_object(mysqli_query($con, $query));