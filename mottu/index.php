<?php
    include("../lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    if($_GET and !$_POST) $_POST = $_GET;

    // if($_POST){
        $dadosLog = print_r($_POST,true);
        file_put_contents("log-".date("YmdHis").".txt", $dadosLog);        
    // }
