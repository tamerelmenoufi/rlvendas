<?php
    include("../../../lib/includes.php");

    function bandeira($cod){
        $banderia = [
            3 => 'AMEX',
            5 => 'MASTERCARD',
            6 => 'DISCOVER'
        ];
        return (($banderia[$cod])?:'VISA');
    }

    function cartao_status($cod){
        $cartao = [
            '00' => 'approved'
        ];
        return (($cartao[$cod])?:'negado');
    }

    if($_POST['acao'] == 'pagar'){

        /*
        {
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
                  "CardNumber":"**************",
                  "Holder":"*****************",
                  "ExpirationDate":"******",
                  "SecurityCode":"***",
                  "SaveCard":"false",
                  "Brand":"Visa"
              },     
              "Type":"CreditCard",
              "Amount":100
            }
         }
         //*/

        $json = '{
            "MerchantOrderId":"'.trim($_POST['MerchantOrderId']).'",
            "Payment":{
              "Installments":1,
              "Capture":true,
              "Authenticate":false,
              "Recurrent":"false",
              "CreditCard":{
                  "CardNumber":"'.str_replace([' '], false,trim($_POST['cardNumber'])).'",
                  "Holder":"'.trim(strtoupper($_POST['Holder'])).'",
                  "ExpirationDate":"'.trim($_POST['ExpirationDate']).'",
                  "SecurityCode":"'.trim($_POST['securityCode']).'",
                  "SaveCard":"false",
                  "Brand":"'.bandeira(trim(substr($_POST['cardNumber'],0,1))).'"
              },     
              "Type":"CreditCard",
              "Amount":'.str_replace([',','.'], false,trim($_POST['amount'])).'
            }
         }'; //"Amount":'.str_replace([',','.'], false,trim($_POST['amount'])).'

         $jsonX = $json;
        $cielo = new Cielo;
        $retorno = $cielo->Transacao($json);
        $json = json_decode($retorno);

        $caixa = mysqli_fetch_object(mysqli_query($con, "select * from caixa where situacao = '0'"));

        $query = "update vendas set 
                                    forma_pagamento = 'credito',
                                    operadora = 'cielo',
                                    caixa = '{$caixa->caixa}',
                                    operadora_id = '{$json->Payment->Tid}',
                                    operadora_situacao = '".cartao_status($json->Payment->ReturnCode)."',
                                    ".(($json->Payment->ReturnCode == '00')?"data_finalizacao = NOW(), situacao = 'pago', ":false)."
                                    operadora_retorno = '{$retorno}'
                    where codigo = '{$_POST['AppVenda']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        if($json->Payment->ReturnCode == '00'){
            $ordem = strtotime("now");
            mysqli_query($con, "UPDATE `vendas_produtos` set situacao = 'p', pago = '1', ordem = {$ordem} where venda = '{$_POST['AppVenda']}'");
            mysqli_query($con, "INSERT INTO `vendas_pagamento` set 
                                                                    venda = '{$_POST['AppVenda']}',
                                                                    caixa = '{$caixa->caixa}',
                                                                    data = NOW(),
                                                                    forma_pagamento = 'credito',
                                                                    valor = '{$_POST['amount']}',
                                                                    operadora = 'cielo',
                                                                    operadora_situacao = '".cartao_status($json->Payment->ReturnCode)."',
                                                                    operadora_retorno = '{$retorno}'
                        ");
        }
    
        echo $retorno;

        exit();


    }

?>
<style>
      .PedidoTopoTitulo{
        position:fixed;
        left:0px;
        top:0px;
        width:100%;
        height:60px;
        background:#fff;
        padding-left:70px;
        padding-top:15px;
        z-index:1;
    }

    .card small{
        font-size:12px;
        text-align:left;
    }
    .card input{
        border:solid 1px #ccc;
        border-radius:3px;
        background-color:#eee;
        color:#333;
        font-size:20px;
        text-align:center;
        margin-bottom:5px;
        width:100%;
        text-transform:uppercase;
    }

    .alertas{
        width:100%;
        text-align:center;
        background-color:#ffffff;
        border:solid 1px #fd3e00;
        color:#ff7d52;
        text-align:center !important;
        border-radius:7px;
        font-size:11px !important;
        font-weight:normal !important;
        padding:5px;
        margin-top:10px;
        margin-bottom:10px;
        display:<?=(($d->tentativas_pagamento < 3)?'block':'none')?>;
    }

</style>

  <div class="PedidoTopoTitulo">
      <h4>Dados do Cartão</h4>
  </div>


  <div class="card mb-3" style="background-color:#fafcff; padding:20px;">
    <div class="row">
            <div class="col-12">
                <div class="card text-white bg-danger mb-3" style="padding:20px;">

                    <small>Nome</small>
                    <input type="text" id="cartao_nome" placeholder="NOME NO CARTÃO" value='' />
                    <small>Número</small>
                    <input inputmode="numeric" maxlength='19' type="text" id="cartao_numero" placeholder="0000 0000 0000 0000" value='' />
                    <div class="row">
                        <div class="col-4">
                            <small>MM</small>
                            <input inputmode="numeric" maxlength='2' type="text" id="cartao_validade_mes" placeholder="00" value='' />
                        </div>
                        <div class="col-4">
                            <small>AAAA</small>
                            <input inputmode="numeric" maxlength='4' type="text" id="cartao_validade_ano" placeholder="0000" value='' />
                        </div>
                        <div class="col-4">
                            <small>CVV</small>
                            <input inputmode="numeric" maxlength='4' type="text" id="cartao_ccv" placeholder="0000" value='' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <small>BANDEIRAS</small>
                            <div class="row">
                                <div class="col">
                                    <h2>
                                        <i class="fa-brands fa-cc-mastercard"></i>
                                        <i class="fa-brands fa-cc-visa"></i>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-secondary btn-block btn-lg" id="Pagar" hom="1" tentativas="<?=$d->tentativas_pagamento?>" loja="<?=$d->id_loja?>">
                    <i class="fa fa-calculator" aria-hidden="true"></i>
                    PAGAR R$ <?=number_format($_POST['valor_total'], 2, ',','.')?>
                </button>

                <!-- <div class="alertas animate__animated animate__fadeIn animate__infinite animate__slower">
                    Atenção, você possui <span tentativa><?=$d->tentativas_pagamento?></span> tentativa(s)!
                </div> -->


            </div>
        </div>
    </div>
</div>
<script>
    $(function(){


        $("#cartao_numero").mask("9999 9999 9999 9999");
        $("#cartao_validade_mes").mask("99");
        $("#cartao_validade_ano").mask("9999");
        $("#cartao_ccv").mask("9999");

        $("#Pagar").click(function(){

            MerchantOrderId = '<?="{$_POST['AppVenda']}-".date("His")?>';
            amount = '<?=number_format($_POST['valor_total'],2,".",false)?>';
            Holder = $("#cartao_nome").val();
            cardNumber = $("#cartao_numero").val();
            ExpirationDate = $("#cartao_validade_mes").val()+'/'+$("#cartao_validade_ano").val();
            securityCode = $("#cartao_ccv").val();


            lista = [];
            lista.push(MerchantOrderId);
            lista.push(amount);
            lista.push(Holder);
            lista.push(cardNumber);
            lista.push(ExpirationDate);
            lista.push(securityCode);
            console.log(lista)


            $.ajax({
                url:"src/produtos/pagar_credito.php",
                type:"POST",
                dataType:"JSON",
                data:{
                    MerchantOrderId,
                    amount,
                    Holder,
                    cardNumber,
                    ExpirationDate,
                    securityCode,
                    AppVenda:'<?=$_SESSION['AppVenda']?>',
                    acao:'pagar'                    
                },
                success:function(dados){
                    console.log(dados)
                    if(dados.Payment?.ReturnCode == '00'){
                        $.alert('Pagamento confirmado com sucesso!');
                        window.location.href='./';
                    }else{
                        erroCode = dados?.Payment?.ReturnCode;
                        erroMessage = dados?.Payment?.ReturnMessage;
                        if(erroCode != '' && erroCode != undefined ){
                            mensagem = `<br>${erroCode}: ${erroMessage}`;
                        }
                        $.alert(`Erro na confirmação do pagamento!${mensagem}`);
                    }
                }
            })
        })

    })
</script>