<?php
error_reporting(0);
include "connect.php";
$con = AppConnect();
include "confYobom.php";
include "config.php";
include "utils.php";
include "fn.php";
include "vendor/mercado_pago/classes.php";
include "vendor/cielo/classes.php";
include "vendor/mottu/classes.php";
// include "AppWapp.php";
$md5 = md5(date("YmdHis"));


if($_SERVER['HTTP_HOST'] == 'app.yobom.com.br'){
    foreach($_GET as $i => $v){
        $d = $i;
    }
    if(strlen($d) == 32){
        header("location:https://yobom.com.br/rlvendas/app/?{$d}");
        exit();
    }else{
        header("location:https://yobom.com.br/rlvendas/app/");
        exit();        
    }
}

if($_SERVER['HTTP_HOST'] == 'yobom.com.br'){

    foreach($_GET as $i => $v){
        $d = $i;
    }

// echo $d; exit();

    if(strlen($d) == 32){
        $query = "select * from mesas where md5(mesa) = '{$d}' and situacao = '1' and deletado != '1'";
        $mesa = mysqli_fetch_object(mysqli_query($con, $query));  
        $_SESSION['AppPedido'] = $mesa->codigo;
        header("location:https://yobom.com.br/rlvendas/app/?n=1");
        exit();
    }

}


$query = "select * from atendentes where codigo = '{$_SESSION['AppGarcom']}'";
$Perfil = mysqli_fetch_object(mysqli_query($con, $query));

$chave_producao = '112233';

$appOpc = [ 
    'garcom' => 'garcom',
    'app' => 'mesa',
    'delivery' => 'delivery',
    'delivery1' => 'delivery',
];

$localApp = $appOpc[explode("/",str_replace("/rlvendas/",false,$_SERVER["PHP_SELF"]))[0]];

$descricao_padrao = 'Saiba a receita e a qualidade dos produtos comercializados pela yobom. Tudo para oferecer a melhor qualidade e os mais deliciosos sabores de Manaus.';

$urlPainel = "https://www.yobom.com.br/rlvendas/panel/";