<?php
    include("../lib/includes.php");

    $q = "select * from clientes where nome != '' limit 10";
    $result = mysqli_query($con, $q);
    while($d = mysqli_fetch_object($result)){
        echo $d->nome."<br>";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    if($_GET and !$_POST) $_POST = $_GET;

    $dadosLog = print_r($_POST,true);

    file_put_contents("log-".date("YmdHis").".txt", $dadosLog);