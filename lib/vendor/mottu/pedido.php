<?php
    include("../../../lib/includes.php");


    
exit();
    $query = "select 
                        a.*,
                        b.nome as Cnome,
                        b.telefone as Ctelefone,
                        b.logradouro as Clogradouro,
                        b.numero as Cnumero,
                        b.ponto_referencia as Cponto_referencia,
                        b.bairro as Cbairro,
                        b.cep as Ccep 
                from vendas a left join clientes b on a.cliente = b.codigo where a.codigo = '61023'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    echo $json = '{
        "code": "'.$d->codigo.'",
        "fullCode": "bk-'.$d->codigo.'",
        "preparationTime": 0,
        "previewDeliveryTime": false,
        "sortByBestRoute": false,
        "deliveries": [
            {
            "code": "'.$d->codigo.'",
            "confirmation": {
                "mottu": true
            },
            "name": "'.$d->Cnome.'",
            "phone": "+55'.trim(str_replace(array(' ','-','(',')'), false, $d->Ctelefone)).'",
            "observation": "'.$d->observacoes.'",
            "address": {
                "street": "'.$d->Clogradouro.'",
                "number": "'.$d->Cnumero.'",
                "complement": "'.$d->Cponto_referencia.'",
                "neighborhood": "'.$d->Cbairro.'",
                "city": "Manaus",
                "state": "AM",
                "zipCode": "'.trim(str_replace(array(' ','-'), false, $d->Ccep)).'"
            },
            "onlinePayment": true,
            "productValue": '.($d->valor+$d->taxa-$d->desconto+$d->acrescimo).'
            }
        ]
        }';
    echo "<hr>";
    $mottu = new mottu;
    $retorno1 = $mottu->NovoPedido($json);
    $retorno = json_decode($retorno1);
    print_r($retorno);
