<?php
    include("./lib/includes.php");

    $json = '{
        "MerchantOrderId":"2014111701",
        "Customer":{
           "Name":"Tamer Mohamed Elmenoufi",
           "Identity":"60110970225",
           "IdentityType":"CPF",
           "Email":"tamer@mohatron.com.br",
           "Birthdate":"1976-08-28",
           "Address":{
                "Street": "Rua Monsenhor Coutinho",
                "Number": "600",
                "Complement": "Edifício Maximino Correia",
                "City": "Manaus",
                "State": "AM",
                "Country": "BR",
                "ZipCode": "69010110"
           },
            "DeliveryAddress": {
            "Street": "Rua Monsenhor Coutinho",
            "Number": "600",
            "Complement": "Edifício Maximino Correia",
            "City": "Manaus",
            "State": "AM",
            "Country": "BR",
            "ZipCode": "69010110"
            },
            "Billing": {
                "Street": "Rua Monsenhor Coutinho",
                "Number": "600",
                "Complement": "Edifício Maximino Correia",
                "Neighborhood": "Centro",
                "City": "Manaus",
                "State": "AM",
                "Country": "BR",
                "ZipCode": "69010110"
            },
        },
        "Payment":{
          "ServiceTaxAmount":0,
          "Installments":1,
          "Interest":"ByMerchant",
          "Capture":true,
          "Authenticate":false,
          "Recurrent":"false",
          "SoftDescriptor":"999999999999",
          "CreditCard":{
              "CardNumber":"4078430099953653",
              "Holder":"tamer mohamed elmenoufi",
              "ExpirationDate":"02/2028",
              "SecurityCode":"977",
              "SaveCard":"false",
              "Brand":"Visa"
          },     
          "Type":"CreditCard",
          "Amount":100
        }
     }';

    $cielo = new Cielo;
    $retorno = $cielo->Transacao($json);
    // $retorno = json_decode($retorno);

    var_dump($retorno);

/////////////////////////////////////////////////