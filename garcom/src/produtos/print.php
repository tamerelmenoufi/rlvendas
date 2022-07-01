<?php

include("../../../lib/includes.php");

if($_SESSION['AppVenda']){
    $dadosParaEnviar = http_build_query(
        array(
            'cod' => $_SESSION['AppVenda'],
            'terminal' => 'terminal2'
        )
    );

    $opcoes = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $dadosParaEnviar
        )
    );

    $contexto = stream_context_create($opcoes);

    $result   = file_get_contents('https://yobom.com.br/rlvendas/painel/vendas/print-2.php', false, $contexto);
}