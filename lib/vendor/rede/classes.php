<?php


    class Rede {

        public $Ambiente;
        public $PV;
        public $TOKEN;

        public function Autenticacao($opc){
            return base64_encode($opc);
        }
        public function Ambiente($opc){
            if($opc == 'homologacao'){
                return 'https://sandbox-erede.useredecloud.com.br/v1/transactions';
            }else{
                return 'https://api.userede.com.br/erede/v1/transactions';
            }
        }

        public function Transacao($d){

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->Ambiente($this->Ambiente));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              "Content-Type: application/json",
              "Authorization: Basic ".$this->Autenticacao($this->PV.":".$this->TOKEN)
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $d);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }

        public function Capture($d){

            $d = json_decode($d);

            $fields = "{
                \"amount\": {$d->amount}
            }";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->Ambiente($this->Ambiente)."/{$d->tid}");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic ".$this->Autenticacao($this->PV.":".$this->TOKEN)
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }

        public function Consulta($d){

            $d = json_decode($d);
            $fields = ['reference' => $d->pedido];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->Ambiente($this->Ambiente)."/?".http_build_query($fields));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              "Content-Type: application/json",
              "Authorization: Basic ".$this->Autenticacao($this->PV.":".$this->TOKEN)
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }

        public function Cancel2(){
            return 'Efetuar Cancrlamento 2!';
        }


    }