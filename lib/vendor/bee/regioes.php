<?php

$chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";
$externalId = 39;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.beedelivery.com.br/api/v1/public/regions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"externalId\": {$externalId}
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: {$chave}"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);