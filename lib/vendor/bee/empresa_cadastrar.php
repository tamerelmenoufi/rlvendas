<?php

$externalId = 39;

$Geral = [
  [
    "cpfCnpj" => "26277931000125",
    "name" => "SP RESTAURANTES LTDA",
    "email" => "ger.bkparaiba@spgrupo.com",
    "uf" => "AM",
    "cidade" => "Manaus",
    "cep" => "69057015",
    "bairro" => "Adrianopolis",
    "rua" => "AV JORNALISTA UMBERTO CALDERARO FILHO LOJA 2",
    "numero" => "1712",
    "telefone" => "92984122099",
    "celular" => "92984122099",
    "latitude" => "-3.0929237",
    "longitude" => "-60.0092208",
    "externalId" => $externalId
  ],

  [
    "cpfCnpj" => "26277931000397",
    "name" => "BURGER KING (Turismo)",
    "email" => "ger.bktaruma@spgrupo.com",
    "uf" => "AM",
    "cidade" => "Manaus",
    "cep" => "69041010",
    "bairro" => "TARUMÃ",
    "rua" => "AV DO TURISMO",
    "numero" => "2726",
    "telefone" => "92984125532",
    "celular" => "92984125532",
    "latitude" => "-3.05129",
    "longitude" => "-60.07791",
    "externalId" => $externalId+1
  ],
  [
    "cpfCnpj" => "26277931000478",
    "name" => "BURGER KING (Shopping Sumaúma)",
    "email" => "ger.bksumauma@spgrupo.com",
    "uf" => "AM",
    "cidade" => "Manaus",
    "cep" => "69095000",
    "bairro" => "CIDADE NOVA",
    "rua" => "AV NOEL NUTELS",
    "numero" => "1762",
    "telefone" => "92984326398",
    "celular" => "92984326398",
    "latitude" => "-3.0308551",
    "longitude" => "-59.977411",
    "externalId" => $externalId+2
  ],
  [
    "cpfCnpj" => "26277931000559",
    "name" => "BURGER KING (Studio-5)",
    "email" => "ger.bkstudio5@spgrupo.com",
    "uf" => "AM",
    "cidade" => "Manaus",
    "cep" => "69073177",
    "bairro" => "CRESPO",
    "rua" => "AV RODRIGO OTÁVIO",
    "numero" => "3555",
    "telefone" => "92984034458",
    "celular" => "92984034458",
    "latitude" => "-3.1247281",
    "longitude" => "-59.9824092",
    "externalId" => $externalId+3
  ],
  [
    "cpfCnpj" => "26277931000630",
    "name" => "BURGER KING (Via Norte)",
    "email" => "ger.bkvianorte@spgrupo.com",
    "uf" => "AM",
    "cidade" => "Manaus",
    "cep" => "69093149",
    "bairro" => "MONTE DAS OLIVEIRA",
    "rua" => "AV ARQUITETO JOSE HENRIQUES B RORIGUES",
    "numero" => "3736",
    "telefone" => "92984113947",
    "celular" => "92984113947",
    "latitude" => "-2.9989892",
    "longitude" => "-60.0024618",
    "externalId" => $externalId+4
  ],
  [
    "cpfCnpj" => "26277931000800",
    "name" => "BURGER KING (Amazonas Shopping)",
    "email" => "ger.bkamazonas2@spgrupo.com",
    "uf" => "AM",
    "cidade" => "Manaus",
    "cep" => "69050902",
    "bairro" => "PARQUE DEZ",
    "rua" => "AV DJALMA BATISTA",
    "numero" => "482",
    "telefone" => "92984388556",
    "celular" => "92984388556",
    "latitude" => "-3.0941363",
    "longitude" => "-60.0226946",
    "externalId" => $externalId+5
  ],
  [
    "cpfCnpj" => "26277931000710",
    "name" => "BURGER KING - PONTA NEGRA",
    "email" => "ger.bkpontanegra@spgrupo.com",
    "uf" => "AM",
    "cidade" => "Manaus",
    "cep" => "69037000",
    "bairro" => "PONTA NEGRA",
    "rua" => "AV CEL TEIXEIRA",
    "numero" => "5705",
    "telefone" => "92984326030",
    "celular" => "92984326030",
    "latitude" => "-3.0847601",
    "longitude" => "-60.0722883",
    "externalId" => $externalId+6
  ],

];


function Cadastrar($dados){

  $chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";

  //$dados = $Geral[4];

  echo $fild = json_encode($dados);


  echo "<br>______________________________________________________________________________________<br>";

  // $dados = "{
  //     \"cpfCnpj\": \"26277931000125\",
  //     \"name\": \"SP RESTAURANTES LTDA\",
  //     \"email\": \"ger.bkparaiba@spgrupo.com\",
  //     \"uf\": \"AM\",
  //     \"cidade\": \"Manaus\",
  //     \"cep\": \"69057015\",
  //     \"bairro\": \"Adrianopolis\",
  //     \"rua\": \"AV JORNALISTA UMBERTO CALDERARO FILHO LOJA 2\",
  //     \"numero\": \"1712\",
  //     \"telefone\": \"92984122099\",
  //     \"celular\": \"92984122099\",
  //     \"latitude\": \"-3.0929237\",
  //     \"longitude\": \"-60.0092208\",
  //     \"externalId\": \"{$externalId}\"
  //   }";

  //  echo json_decode($dados);


  //exit();

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.beedelivery.com.br/api/v1/public/companies/new");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_POST, TRUE);

  curl_setopt($ch, CURLOPT_POSTFIELDS, $fild);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: {$chave}"
  ));

  $response = curl_exec($ch);
  curl_close($ch);

  var_dump($response);

  echo "<hr>";

}


for($i=0;$i<count($Geral);$i++){
  Cadastrar($Geral[$i]);
}