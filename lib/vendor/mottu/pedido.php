<?php
    include("../../../lib/includes.php");


    ////////////////CONSULTAR FRETE////////////////////////////////
    // $mottu = new mottu;
    // $json = "{
    //     \"previewDeliveryTime\": true,
    //     \"sortByBestRoute\": false,
    //     \"deliveries\": [
    //         {
    //         \"orderRoute\": 112233,
    //         \"address\": {
    //             \"street\": \"Bruxelas\",
    //             \"number\": \"15\",
    //             \"complement\": \"\",
    //             \"neighborhood\": \"Planalto\",
    //             \"city\": \"Manaus\",
    //             \"state\": \"AM\",
    //             \"zipCode\": \"69045260\"
    //         },
    //         \"onlinePayment\": true
    //         }
    //     ]
    //     }";
    
    // echo $mt = $mottu->calculaFrete($json);
    // $valores = json_decode($mt);
    // echo "<hr>";
    // echo $taxa_entrega = $valores->deliveryFee;


//exit();

    //////////////////////SOLICITAR ENTREGA//////////////////////////////////
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

    // echo $json = '{
    //     "code": "'.$d->codigo.'",
    //     "fullCode": "bk-'.$d->codigo.'",
    //     "preparationTime": 0,
    //     "previewDeliveryTime": false,
    //     "sortByBestRoute": false,
    //     "deliveries": [
    //         {
    //         "code": "'.$d->codigo.'",
    //         "confirmation": {
    //             "mottu": true
    //         },
    //         "name": "'.$d->Cnome.'",
    //         "phone": "+55'.trim(str_replace(array(' ','-','(',')'), false, $d->Ctelefone)).'",
    //         "observation": "'.$d->observacoes.'",
    //         "address": {
    //             "street": "'.$d->Clogradouro.'",
    //             "number": "'.$d->Cnumero.'",
    //             "complement": "'.$d->Cponto_referencia.'",
    //             "neighborhood": "'.$d->Cbairro.'",
    //             "city": "Manaus",
    //             "state": "AM",
    //             "zipCode": "'.trim(str_replace(array(' ','-'), false, $d->Ccep)).'"
    //         },
    //         "onlinePayment": true,
    //         "productValue": '.($d->valor+$d->taxa-$d->desconto+$d->acrescimo).'
    //         }
    //     ]
    //     }';


    // echo $json = '{
    //     "code": "'.$d->codigo.'",
    //     "fullCode": "bk-'.$d->codigo.'",
    //     "preparationTime": 0,
    //     "previewDeliveryTime": false,
    //     "sortByBestRoute": false,
    //     "deliveries": [
    //         {
    //         "code": "'.$d->codigo.'",
    //         "confirmation": {
    //             "mottu": true
    //         },
    //         "name": "'.$d->Cnome.'",
    //         "phone": "+55'.trim(str_replace(array(' ','-','(',')'), false, $d->Ctelefone)).'",
    //         "observation": "'.$d->observacoes.'",
    //         "address": {
    //             "street": "Bruxelas",
    //             "number": "15",
    //             "complement": "",
    //             "neighborhood": "Planalto",
    //             "city": "Manaus",
    //             "state": "AM",
    //             "zipCode": "69045260"
    //         },
    //         "onlinePayment": true,
    //         "productValue": '.($d->valor+$d->taxa-$d->desconto+$d->acrescimo).'
    //         }
    //     ]
    //     }';
        
    echo "<hr>";
    // $mottu = new mottu;
    // $retorno1 = $mottu->NovoPedido($json);
    // $retorno = json_decode($retorno1);
    // print_r($retorno);

    /////////////////////////// CANCELAR ENTREGA/////////////////////////////////////
    
    // exit();

    $json = '{
        "orderId": "10759000",
        "reason": "Pedido cancelado, cliente desistiu"
      }';
        
    echo "<hr>";
    $mottu = new mottu;
    $retorno1 = $mottu->cancelarPedido($json);
    $retorno = json_decode($retorno1);
    print_r($retorno);


    echo "<hr>";
    // $mottu = new mottu;
    // $retorno1 = $mottu->ConsultarPedido(9848170);
    // $retorno = json_decode($retorno1);
    // print_r($retorno);