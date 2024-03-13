<?php

include("../lib/includes_off.php");


$query = "select 
                a.*,
                b.nome as Cnome,
                b.telefone as Ctelefone,
                b.logradouro as Clogradouro,
                b.numero as Cnumero,
                b.ponto_referencia as Cponto_referencia,
                b.bairro as Cbairro,
                b.cep as Ccep,
                b.coordenadas as Ccoordenadas,
                a.delivery->'$.id'
                from vendas a 
                left join clientes b on a.cliente = b.codigo 
                where a.app = 'delivery' and 
                a.situacao = 'pago' and 
                a.deletado != '1' and
                a.data_finalizacao > 0 and
                DATE_ADD(a.data_finalizacao, INTERVAL +10 minute) < NOW() and
                a.delivery->'$.id' is NULL  limit 10";

$result = mysqli_query($con, $query);
while($d = mysqli_fetch_object($result)){

    if(trim($d->Ccoordenadas)){
        list($latitude, $longitude) = explode(",",$d->Ccoordenadas);
        if($latitude, $longitude){
            $coordenadas = ',
            "latitude": '.$latitude.',
            "longitude": '.$longitude.'
            ';            
        }else{
            $coordenadas = false;
        }
    }else{
        $coordenadas = false;
    }

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
                "zipCode": "'.trim(str_replace(array(' ','-'), false, $d->Ccep)).'"'.$coordenadas.'
            },
            "onlinePayment": true,
            "productValue": '.($d->valor+$d->taxa-$d->desconto+$d->acrescimo).'
            }
        ]
    }';

    $mottu = new mottu;
    $retorno1 = $mottu->NovoPedido($json);
    $retorno = json_decode($retorno1);
    print_r($retorno);

}

?>