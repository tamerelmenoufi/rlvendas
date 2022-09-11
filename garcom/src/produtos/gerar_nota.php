<?php

    include("../../../lib/includes.php");

    $postdata = http_build_query(
        array(
            'id' => $_POST['venda'], // Receivers phonei
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context = stream_context_create($opts);
    $result = file_get_contents('https://yobom.com.br/rlvendas/nf/emissorNF.php', false, $context);


    $query = "select * from vendas where codigo = '{$_POST['venda']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    if($d->nf_status == 'aprovado'){
        $retorno = [
            'status' => true,
            'nota' => $d->nf_numero
        ];
    }else{
        $retorno = [
            'status' => false,
            'error' => "Ocorreu algum problema,".$d->nr_error
        ];
    }
    echo json_encode($retorno);