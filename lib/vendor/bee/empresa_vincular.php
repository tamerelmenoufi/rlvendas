<?php

function Vincular($Id, $t, $c){

  $chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";
  $externalId = $Id;

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.beedelivery.com.br/api/v1/public/companies/link");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_POST, TRUE);

  curl_setopt($ch, CURLOPT_POSTFIELDS, "{
    \"externalId\": {$externalId},
    \"docType\": \"{$t}\",
    \"doc\": \"{$c}\"
  }");

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: {$chave}"
  ));

  $response = curl_exec($ch);
  curl_close($ch);

  var_dump($response);
  echo "<hr>";
}

$e = [
  [39,'j',26277931000125],
  [40,'j',26277931000397],
  [41,'j',26277931000479],
  [42,'j',26277931000559],
  [44,'j',26277931000800],
  [45,'j',26277931000710],
];

for($i=0;$i<count($e);$i++){
  Vincular($e[$i][0], $e[$i][1], $e[$i][2]);
}