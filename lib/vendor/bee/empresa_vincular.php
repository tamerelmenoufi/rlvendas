<?php

function Vincular($Id, $t, $c){

  $chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";

  $field = ['externalId' => $Id, 'docType' => $t, 'doc' => $c];

  $field = json_encode($field);

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.beedelivery.com.br/api/v1/public/companies/link");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_POST, TRUE);

  curl_setopt($ch, CURLOPT_POSTFIELDS, $field);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: {$chave}"
  ));

  $response = curl_exec($ch);
  curl_close($ch);
  echo $Id."<br>";
  var_dump($response);
  echo "<hr>";
}

$e = [
  [46,'F',93847122487],
  // [40,'J',26277931000397],
  // [41,'J',26277931000478],
  // [42,'J',26277931000559],
  // [44,'J',26277931000800],
  // [45,'J',26277931000710]
];

for($i=0;$i<count($e);$i++){
  Vincular($e[$i][0], $e[$i][1], $e[$i][2]);
}

//TETSTE