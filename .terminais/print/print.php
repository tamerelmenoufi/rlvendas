<?php
/*
    $dadosParaEnviar = http_build_query(
        array(
            't' => 'terminal1'
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

 //*/

    $result   = file_get_contents('https://yobom.com.br/rlvendas/painel/vendas/print/terminal1-c.txt');

    if($result){
	file_put_contents("docs/terminal1.txt",$result);
	system("cat docs/terminal1.txt > /dev/usb/lp0");




    $dadosParaEnviar = http_build_query(
        array(
            't' => 'terminal1'
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

    $result   = file_get_contents('https://yobom.com.br/rlvendas/painel/vendas/print/remove.php', false, $contexto);


    }
