<?php

include("../../lib/includes.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
$_POST = json_decode(file_get_contents('php://input'), true);

file_put_contents(
    'x.txt',
    print_r($_POST, true)."\n\n\n"
);

$Json = '{
    "transaction_amount": '.$_POST['transaction_amount'].',
    "token": "'.$_POST['token'].'",
    "description": "'.$_POST['description'].'",
    "installments": '.$_POST['installments'].',
    "payment_method_id": "'.$_POST['payment_method_id'].'",
    "issuer_id": '.$_POST['issuer_id'].',
    "payer": {
      "email": "'.$_POST['payer']['email'].'"
    }
}';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, $Json);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "accept: application/json",
    "Content-Type: application/json",
    "Authorization: Bearer {$cYb['mercado_pago']['producao']['TOKEN']}"
));

$response = curl_exec($ch);
curl_close($ch);
$operadora_retorno = $response;
$resposta = json_decode($response);


if($resposta->status == 'approved'){


    $queryL = "select * from vendas where codigo = '{$_SESSION['AppVenda']}'";
    $resultL = mysqli_query($con, $queryL);
    $v = mysqli_fetch_object($resultL);

    $codigos = [];
        $query = "SELECT * FROM vendas_produtos WHERE venda = '{$_SESSION['AppVenda']}' and situacao = 'b'";
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
                forma_pagamento = 'credito',
                operadora = 'mercadopago',
                operadora_id='{$resposta->id}',
                operadora_situacao='{$resposta->status}',
                operadora_retorno='{$response}',
                situacao = '".(($resposta->status == 'approved')?'c':'n')."'
            where codigo = '{$_SESSION['AppVenda']}'";

        mysqli_query($con, $q);

        sisLog(
            [
                'query' => $q,
                'file' => $_SERVER["PHP_SELF"],
                'sessao' => $_SESSION,
                'registro' => $_SESSION['AppVenda']
            ]
        );

        list($valorPago) = mysqli_fetch_row(mysqli_query($con, "select sum(valor) from vendas_pagamento where venda = '{$_SESSION['AppVenda']}' and operadora_situacao = 'approved'"));

        $caixa = mysqli_fetch_object(mysqli_query($con, "select * from caixa where situacao = '0'"));

        $q = "INSERT INTO vendas_pagamento set
                            caixa = '{$caixa->caixa}',
                            venda = '{$_SESSION['AppVenda']}',
                            data = NOW(),
                            forma_pagamento = 'credito',
                            valor = '".($v->total - $valorPago)."',
                            operadora = 'mercado_pago',
                            operadora_situacao = 'approved',
                            operadora_retorno = '{$response}'
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

        $q = "INSERT INTO status_venda set
                            retorno = '{$response}',
                            data = NOW(),
                            forma_pagamento = 'credito',
                            operadora = 'mercado_pago',
                            venda = '{$_SESSION['AppVenda']}'
                    ";

        mysqli_query($con, $q);
                    sisLog(
                        [
                            'query' => $q,
                            'file' => $_SERVER["PHP_SELF"],
                            'sessao' => $_SESSION,
                            'registro' => $_SESSION['AppVenda']
                        ]
                    );
}else{

    $query = "update vendas set
                                forma_pagamento = 'credito',
                                operadora = 'mercadopago',
                                operadora_id='{$resposta->id}',
                                operadora_situacao='{$resposta->status}',
                                operadora_retorno='{$response}',
                                situacao = '".$resposta->status."'
                where codigo = '{$_SESSION['AppVenda']}'";

    mysqli_query($con, $query);

    sisLog(
        [
            'query' => $query,
            'file' => $_SERVER["PHP_SELF"],
            'sessao' => $_SESSION,
            'registro' => $_SESSION['AppVenda']
        ]
    );

}





file_put_contents(
                    'x.txt',
                    print_r($_POST, true)."\n\n\n".
                    date("d/m/Y H:i:s")."\n\n\n\n".
                    $Json."\n\n\n\n".
                    $response."\n\n\n\n".
                    print_r($resposta, true)."\n\n\n\n".
                    $query
                );
