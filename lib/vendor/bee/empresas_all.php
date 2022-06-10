<?php

$chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.beedelivery.com.br/api/v1/public/companies/getAll");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "Authorization: {$chave}"
));

$response = curl_exec($ch);
curl_close($ch);

$dados = json_decode($response);

print_r($dados->data);

echo "<h4>Clientes:</h4>";
foreach($dados->data as $i => $v){
  echo "Cliente {$v->tipo} : {$v->external_id} - ".(($v->tipo == 'J')?$v->cnpj:$v->cpf)."<br><hr>";
}