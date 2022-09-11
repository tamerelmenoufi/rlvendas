<?php

include("../../../lib/includes.php");

$local = [
    'terminal1' => '1',
    'terminal2' => '2',
];

if($_SESSION['AppVenda']){
    $dadosParaEnviar = http_build_query(
        array(
            'cod' => (($_POST['venda'])?$_POST['venda']:$_SESSION['AppVenda']),
            'terminal' => $_POST['impressora']
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

    $result   = file_get_contents('https://yobom.com.br/rlvendas/painel/vendas/print-'.$local[$_POST['impressora']].'.php', false, $contexto);
}