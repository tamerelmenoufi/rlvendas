<?php

class mottu {

    public $ambiente = 'producao'; //homologacao ou producao

    public function Ambiente($opc){
        if($opc == 'homologacao'){
            return 'https://integrations.mottu.io/delivery';
        }else{
            return 'https://integrations.mottu.cloud/delivery';
        }
    }

    public function apiKey($opc){
        global $cYb;
        if($opc == 'homologacao'){
            return $cYb['mottu']['homologacao']['TOKEN-API'];
        }else{
            return $cYb['mottu']['producao']['TOKEN-API'];
        }
    }

    public function integradora($opc){
        global $cYb;
        if($opc == 'homologacao'){
            return $cYb['mottu']['homologacao']['TOKEN-INTEGRATOR'];
        }else{
            return $cYb['mottu']['producao']['TOKEN-INTEGRATOR'];
        }
    }

    public function NovoPedido($json){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente).'/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente),
            // 'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response; //."\n".$this->Ambiente($this->ambiente)."\n".$this->apiKey($this->ambiente, $loja)."\n";

    }


    public function ConsultarPedido($pedido){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente).'/orders/'.$pedido,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente),
            // 'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response; //."\n".$this->Ambiente($this->ambiente)."\n".$this->apiKey($this->ambiente, $loja)."\n";

    }


    public function cancelarPedido($json){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente).'/orders/cancel',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente),
            // 'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response; //."\n".$this->Ambiente($this->ambiente)."\n".$this->apiKey($this->ambiente, $loja)."\n";
    }

    public function calculaFrete($json){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente)."/orders/preview",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente),
            // 'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response."\n".$this->Ambiente($this->ambiente)."/orders/preview"."\n".$this->apiKey($this->ambiente)."\n";

    }

    public function webhook($json){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente)."/webhooks/handle",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente),
            // 'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response; //."\n".$this->Ambiente($this->ambiente)."\n".$this->apiKey($this->ambiente, $loja)."\n";

    }


}