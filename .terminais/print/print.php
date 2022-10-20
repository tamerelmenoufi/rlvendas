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

    $t = 3;

    $result   = file_get_contents('https://yobom.com.br/rlvendas/painel/vendas/print/terminal'.$t.'-c.txt');

    $prov = file_get_contents("docs/provisorio.txt");

    if($result and $prov != 'false'){

        file_put_contents("docs/provisorio.txt",md5($result));

        file_put_contents("docs/terminal".$t.".txt",$result);
    	system("cat docs/terminal".$t.".txt > /dev/us/lp0");


        $dadosParaEnviar = http_build_query(
            array(
                't' => 'terminal'.$t
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

        file_put_contents("docs/provisorio.txt",'false');

    }
