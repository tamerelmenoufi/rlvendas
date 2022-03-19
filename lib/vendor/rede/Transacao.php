<?php

    $rede = new Rede;
    $rede->Ambiente = 'homologacao';
    $rede->PV = '19348375';
    $rede->TOKEN = '2b4e31d3a75b429c9ef5fdd02f2b5c59';


    $x = $rede->Transacao('{
        "capture": false,
        "kind": "credit",
        "reference": "pedido3",
        "amount": 2099,
        "installments": 2,
        "cardholderName": "John Snow",
        "cardNumber": "5448280000000007",
        "expirationMonth": 12,
        "expirationYear": 2028,
        "securityCode": "235",
        "softDescriptor": "string",
        "subscription": false,
        "origin": 1,
        "distributorAffiliation": 0,
        "brandTid": "string"
    }');