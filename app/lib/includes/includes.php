<?php
    @session_start();
    error_reporting(0);
    ini_set("display_errors", 0 );
    $md5 = md5(date("dmyHis"));

    function dataBr($data){
        list($dt, $H) = explode(" ", $data);
        list($ano, $mes, $dia) = explode("-",$dt);
        return $dia."/".$mes."/".$ano.(($H)?(' '.$H):false);
    }

    function dataMysql($data){
        list($dt, $H) = explode(" ", $data);
        list($dia, $mes, $ano) = explode("/",$dt);
        return $ano."-".$mes."-".$dia.(($H)?' '.$H:false);
    }

    if($_POST){
        $post = implode("|", $_POST);
    }

    if($_GET){
        $get = implode("|", $_GET);
    }
