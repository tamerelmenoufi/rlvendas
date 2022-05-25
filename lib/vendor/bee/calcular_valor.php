<?php

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://integrationtest.beedelivery.com.br/api/v1/public/fees/calculate");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_POST, TRUE);

    curl_setopt($ch, CURLOPT_POSTFIELDS, "{
        \"vehicle\": \"M\",
        \"needReturn\": \"S\",
        \"origin\": {
            \"externalId\": 26277931000125

        },
        \"destination\": {
            \"type\": \"COORDS\",
            \"address\": {
                \"latitude\": -3.1290315,
                \"longitude\": -60.02384969999999,
            }
        }
    }");

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: {$chave}"
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    var_dump($response);