<?php
include("../includes.php");
#include "./conf.php";


$query = "SELECT * FROM produtos";
$result = mysqli_query($con, $query);

$json = [];

$i = 0;

$values = [];

while ($d = mysqli_fetch_object($result)) {
    $dados = json_decode($d->detalhes);

    foreach ($dados as $key => $value) {
        $json[$i][] = [
            "medida" => $key,
            "valor" => $value[0],
            "quantidade" => $value[1],
        ];
    }

    $i++;

    $json_encode = json_encode($json);

    $values[] = "('{$d->codigo}', '{$json_encode}')";
}

print_r($json);die;
$values = implode(", ", $values);

$query = "INSERT INTO produtos (codigo, detalhes) "
    . "VALUES {$values} ON DUPLICATE KEY UPDATE detalhes = VALUES(detalhes)";


if (mysqli_query($con, $query)) {
    echo "OK";
} else {
    var_dump(mysqli_error($con));
}
#print_r(json_encode($json));
