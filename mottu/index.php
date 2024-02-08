<?php
    include("../lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $json = file_get_contents('php://input');
    $_POST = json_decode($json, true);

    if($_GET and !$_POST) $_POST = $_GET;

    if($_POST){

        $query = "insert into delivery_logs set 
                                                venda = '{$_POST['CodigoExterno']}',
                                                data = NOW(),
                                                delivery = '{$json}'
                ";
        mysqli_query($con, $query);

        $query = "UPDATE vendas set delivery = {$json} where codigo = '{$_POST['CodigoExterno']}'";
        mysqli_query($con, $query);

        $dadosLog = print_r($_POST,true);
        file_put_contents("log-".date("YmdHis").".txt", $dadosLog);        
    }
