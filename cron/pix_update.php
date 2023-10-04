<?php

include("../lib/includes_off.php");


$queryL = "select * from vendas where operadora_situacao = 'pending'";
$resultL = mysqli_query($con, $queryL);
while($v = mysqli_fetch_object($resultL)){

    $PIX = new MercadoPago;
    $retorno = $PIX->ObterPagamento($v->operadora_id);
    echo $operadora_retorno = $retorno;
    $retorno = json_decode($retorno);

    echo "<hr>";
    if($retorno->status == 'approved'){

        $codigos = [];
        $query = "SELECT * FROM vendas_produtos WHERE venda = '$v->codigo' and situacao = 'b'";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
            $codigos[] = $d->codigo;
        }
        $codigos = implode(",", $codigos);

        $ordem = strtotime("now");

        $query = "UPDATE vendas_produtos SET situacao = 'p', ordem = '{$ordem}', pago = '1' WHERE codigo in ({$codigos})";
        mysqli_query($con, $query);
        sisLog(
            [
                'query' => $query,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $codigos
            ]
        );

        $q = "update vendas set
                            operadora_situacao = '{$retorno->status}',
                            operadora_retorno = '{$operadora_retorno}',
                            situacao = 'preparo'
                        where codigo = '{$v->codigo}'
                    ";
        mysqli_query($con, $q);
        sisLog(
            [
                'query' => $q,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $v->codigo
            ]
        );

        list($valorPago) = mysqli_fetch_row(mysqli_query($con, "select sum(valor) from vendas_pagamento where venda = '{$v->codigo}' and operadora_situacao = 'approved'"));

        $caixa = mysqli_fetch_object(mysqli_query($con, "select * from caixa where situacao = '0'"));

        $q = "INSERT INTO vendas_pagamento set
                            caixa = '{$caixa->caixa}',
                            venda = '{$v->codigo}',
                            data = NOW(),
                            forma_pagamento = 'pix',
                            valor = '".($v->total - $valorPago)."',
                            operadora = 'mercado_pago',
                            operadora_situacao = 'approved',
                            operadora_retorno = '{$operadora_retorno}'
                    ";
        mysqli_query($con, $q);
                    sisLog(
                        [
                            'query' => $q,
                            'file' => $_SERVER["PHP_SELF"],
                            'sessao' => $_SESSION,
                            'registro' => mysqli_insert_id($con)
                        ]
                    );

        $q = "UPDATE status_venda set
                            retorno = '{$operadora_retorno}',
                            data = NOW()
                            where retorno->>'$.id' = '{$v->operadora_id}'
                    ";

        mysqli_query($con, $q);
                    sisLog(
                        [
                            'query' => $q,
                            'file' => $_SERVER["PHP_SELF"],
                            'sessao' => $_SESSION,
                            'registro' => $v->operadora_id
                        ]
                    );
    }else{

        $q = "update vendas set
                            operadora_situacao = '{$retorno->status}',
                            operadora_retorno = '{$operadora_retorno}'
                        where codigo = '{$v->codigo}'
                    ";
        mysqli_query($con, $q);
        sisLog(
            [
                'query' => $q,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $v->codigo
            ]
        );
    }

}

?>