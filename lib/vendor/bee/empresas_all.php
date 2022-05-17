<?php

$chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://integrationtest.beedelivery.com.br/api/v1/public/companies/getAll");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: {$chave}"
));

$response = curl_exec($ch);
curl_close($ch);

$dados = json_decode($response);

echo "<hr>";
foreach($dados as $i => $v){
  echo "{$i} : {$v} <br>";
}