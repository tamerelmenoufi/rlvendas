<?php

$chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";
$external_Id = 38;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://integrationtest.beedelivery.com.br/api/v1/public/companies/{$external_Id}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: {$chave}"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);