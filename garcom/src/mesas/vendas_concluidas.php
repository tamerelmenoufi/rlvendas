<?php
    include("../../../lib/includes.php");

    $query = "SELECT * FROM `vendas` where situacao = 'pagar' and deletado != '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($resul)){
        echo "{$d->codigo} - mesa ({$d->mesa}) valor: {$d->total}<br>";
    }