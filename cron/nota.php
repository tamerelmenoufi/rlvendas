<?php

include("../lib/includes_off.php");

# SELECT * FROM `vendas` where codigo in (select codigo from vendas_pagamento where data_pedido like '%2024-01-20%' and forma_pagamento = 'credito') and deletado != '1' and situacao = 'pago';

$query = "SELECT * FROM `vendas` where /*codigo in (select codigo from vendas_pagamento where data_pedido >= '2024-07-17 00:00:00' and forma_pagamento = 'credito') and*/ data_pedido >= '2024-07-17 00:00:00' and deletado != '1' and situacao = 'pago' and nf_status = '' limit 1";
$result = mysqli_query($con, $query);
$d = mysqli_fetch_object($result);

if(!$d->codigo) exit();

$postdata = http_build_query(
    array(
        'id' => $d->codigo, // Receivers phonei
        'cpf' => $d->cpf, // Receivers phonei
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

echo "<pre>";
echo $result;
echo "</pre>";

echo "<br><br>";
echo $d->codigo;

// $query1 = "select * from vendas where codigo = '$d->codigo'";
// $result1 = mysqli_query($con, $query1);
// $d1 = mysqli_fetch_object($result1);

if($d->nf_status != 'aprovado'){
//     $retorno = [
//         'status' => true,
//         'nota' => $d->nf_numero
//     ];

// echo $query = "UPDATE `vendas` set nf_status = 'erro', nr_error = '{$result}' where codigo  = '{$d->codigo}'";
// $result = mysqli_query($con, $query);

}

// else{
//     $retorno = [
//         'status' => false,
//         'error' => "Ocorreu algum problema,".$result.' - '.$query.' - '.$d->nr_error
//     ];
// }
// echo trim(json_encode($retorno));