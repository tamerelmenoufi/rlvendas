<?php

    $chave = "7ee80ecf9002e205789139ef9179b3b4c3dbe776";

    // LISTAR AS REGIÕES

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.beedelivery.com.br/api/v1/public/regions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);

    curl_setopt($ch, CURLOPT_POST, TRUE);

    curl_setopt($ch, CURLOPT_POSTFIELDS, "{
      \"externalId\": 37
    }");

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "Authorization: $chave"
    ));

    echo $response = curl_exec($ch);
    curl_close($ch);

    $regioes = json_decode($response);

    echo "<table width='100%'>";
    echo "<tr>";
    echo "<td>Codigo</td>";
    echo "<td>Local</td>";
    echo "<td>Valor Entrega</td>";
    echo "<td>Valor Retorno</td>";
    echo "<td>Entrega e Retorno</td>";
    echo "</tr>";

    for($i=0;$i<count($regioes->regions);$i++){

        echo "<tr>";
        echo "<td>".$regioes->regions[$i]->region_id."</td>";
        echo "<td>".utf8_decode($regioes->regions[$i]->description)."</td>";
        echo "<td>".$regioes->regions[$i]->fee."</td>";
        echo "<td>".$regioes->regions[$i]->returnFee."</td>";
        echo "<td>".(($regioes->regions[$i]->returnFee) + ($regioes->regions[$i]->fee))."</td>";
        echo "</tr>";


        set_time_limit(90);

    }


    echo "</table>";

?>