<?php
include("../includes.php");
#include "./conf.php";

$query = "SELECT * FROM produtos";
$result = mysqli_query($con, $query);

$json = [];
$values = [];
$i = 0;

while ($d = mysqli_fetch_object($result)) {
    $dados = json_decode($d->detalhes);

    #print_r($dados);

    foreach ($dados as $key => $value) {
        #print_r($key);
        $json[$i][$key] = [
            "valor" => $value[0],
            "quantidade" => $value[1],
        ];
    }

    $json_encode = json_encode($json[$i]);
    $values[] = "('{$d->codigo}', '{$json_encode}')";
    $i++;
}

$values = implode(", ", $values);

$query = "INSERT INTO produtos (codigo, detalhes) "
    . "VALUES {$values} ON DUPLICATE KEY UPDATE detalhes = VALUES(detalhes)";


if (mysqli_query($con, $query)) {
    echo "OK";
} else {
    var_dump(mysqli_error($con));
}
#print_r(json_encode($json));
