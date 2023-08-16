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

$resposta = json_decode($response);



$query = "update vendas set
                            forma_pagamento = 'credito',
                            operadora = 'mercadopago',
                            operadora_id='{$resposta->id}',
                            operadora_situacao='{$resposta->status}',
                            operadora_retorno='{$response}',
                            situacao = '".(($resposta->status == 'approved')?'c':'n')."'
            where codigo = '{$_SESSION['AppVenda']}'";

mysqli_query($con, $query);


file_put_contents(
                    'x.txt',
                    print_r($_POST, true)."\n\n\n".
                    date("d/m/Y H:i:s")."\n\n\n\n".
                    $Json."\n\n\n\n".
                    $response."\n\n\n\n".
                    print_r($resposta, true)."\n\n\n\n".
                    $query
                );
