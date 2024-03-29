<?php

// $cYb['cielo']['merchantKey']
// $cYb['cielo']['merchantId']
// $cYb['cielo']['EC']

class Cielo {

    public $Ambiente = 'producao'; //homologacao ou producao

    public function Ambiente($opc){
        if($opc == 'homologacao'){
            return 'https://api.cieloecommerce.cielo.com.br';
        }else{
            return 'https://api.cieloecommerce.cielo.com.br';
        }
    }

    public function Transacao($d){
        global $cYb;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->Ambiente($this->Ambiente)."/1/sales/");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "MerchantId: {$cYb['cielo']['merchantId']}",
            "Content-Type: application/json",
            "MerchantKey: {$cYb['cielo']['merchantKey']}"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;

    }


}