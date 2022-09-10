<?php
    include("../../../lib/includes.php");

    $data_limite = date( "Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d") - 1, date("Y")));

    $query = "SELECT * FROM `vendas` where ((data_finalizacao >= '{$data_limite}' and situacao = 'pago') or situacao = 'pagar') and deletado != '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        echo "{$d->codigo} - mesa ({$d->mesa}) valor: {$d->total}<br>";
    }