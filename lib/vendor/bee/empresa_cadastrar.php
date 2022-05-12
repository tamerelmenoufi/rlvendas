<?php

$chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";
$externalId = 39;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://integrationtest.beedelivery.com.br/api/v1/public/companies/new");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"cpfCnpj\": \"26277931000125\",
  \"name\": \"BURGER KING (Drive Humberto Calderaro)\",
  \"email\": \"ger.bkparaiba@spgrupo.com\",
  \"uf\": \"AM\",
  \"cidade\": \"Manaus\",
  \"cep\": \" 69057015\",
  \"bairro\": \"Adrianopolis\",
  \"rua\": \"AV JORNALISTA UMBERTO CALDERARO FILHO LOJA 2 \",
  \"numero\": 1712,
  \"telefone\": \"92984122099\"
  \"celular\": \"92984122099\",
  \"latitude\": -3.0929237,
  \"longitude\": -60.0092208,
  \"externalId\": \"{$externalId}\"
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: {$chave}"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);