<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/cegonha/painel/lib/includes.php");

    $query = "select
                    *
                from vendas
                where codigo = '{$_SESSION['codVenda']}'";

    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $pos =  strripos($d->nome, " ");

?>
<style>

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
        margin-bottom:15px;
        width:100%;
        text-transform:uppercase;
    }
    .status_pagamento{
        width:100%;
        text-align:center;
    }
</style>
<div style="margin-top:30px;">
    <div class="col">
        <div class="row">
                <div class="col-12">
                    <div class="card mb-3" style="background-color:#fafcff; padding:20px;">
                        <p style="text-align:center">
                            <?php

                                $pedido = str_pad($d->codigo, 6, "0", STR_PAD_LEFT);


                                $PIX = new MercadoPago;
                                $retorno = $PIX->ObterPagamento($d->operadora_id);
                                $operadora_retorno = $retorno;
                                $dados = json_decode($retorno);

                                if( $d->operadora_id and
                                    $d->operadora == 'mercadopago' and
                                    $d->total == $dados->transaction_amount
                                    ){

                                    // echo "<pre>";
                                    // print_r($dados);
                                    // echo "</pre>";

                                    $operadora_id = $dados->id;
                                    $forma_pagamento = $dados->payment_method_id;
                                    $operadora_situacao = $dados->status;
                                    $qrcode = $dados->point_of_interaction->transaction_data->qr_code;
                                    $qrcode_img = $dados->point_of_interaction->transaction_data->qr_code_base64;

                                }else{
                                    //ESSAS DUAS LINHAS SÃO PARA A SOLICITAÇÃO DA ENTREGA BEE
                                    // $BEE = new Bee;
                                    // echo $retorno = $BEE->NovaEntrega($d->codigo);
                                    //////////////////////////////////////////////////////////

                                    //AQUI É A GERAÇÃO DA COBRANÇA PIX

                                    $PIX = new MercadoPago;
                                    // "transaction_amount": '.$d->total.',
                                    // "transaction_amount": 2.11,

                                    // {
                                    //     "transaction_amount": '.$d->total.',
                                    //     "description": "Venda '.$pedido.' - Chá Revelação",
                                    //     "payment_method_id": "pix",
                                    //     "payer": {
                                    //     "email": "tamer@mohatron.com.br",
                                    //     "first_name": "Tamer",
                                    //     "last_name": "Elmenoufi",
                                    //     "identification": {
                                    //         "type": "CPF",
                                    //         "number": "60110970225"
                                    //     },
                                    //     "address": {
                                    //         "zip_code": "69010110",
                                    //         "street_name": "Monsenhor Coutinho",
                                    //         "street_number": "600",
                                    //         "neighborhood": "Centro",
                                    //         "city": "Manaus",
                                    //         "federal_unit": "AM"
                                    //     }
                                    //     }
                                    // }

                                    $retorno = $PIX->Transacao('{
                                        "transaction_amount": '.$d->total.',
                                        "description": "Venda '.$pedido.' - Chá Revelação",
                                        "payment_method_id": "pix",
                                        "payer": {
                                        "email": "a.carlavasc@gmail.com",
                                        "first_name": "Ana",
                                        "last_name": "Carla",
                                        "identification": {
                                            "type": "CPF",
                                            "number": "83352848220"
                                        },
                                        "address": {
                                            "zip_code": "69058780",
                                            "street_name": "Rua Marquês de Maranhão",
                                            "street_number": "721",
                                            "neighborhood": "Flores",
                                            "city": "Manaus",
                                            "federal_unit": "AM"
                                        }
                                        }
                                    }');

                                    // echo $retorno;
                                    $dados = json_decode($retorno);

                                    $operadora_id = $dados->id;
                                    $forma_pagamento = $dados->payment_method_id;
                                    $operadora_situacao = $dados->status;
                                    $qrcode = $dados->point_of_interaction->transaction_data->qr_code;
                                    $qrcode_img = $dados->point_of_interaction->transaction_data->qr_code_base64;


                                    if($operadora_id){

                                        $q = "insert into status_venda set
                                        venda = '{$d->codigo}',
                                        operadora = 'mercado_pago',
                                        tipo = 'pix',
                                        data = NOW(),
                                        retorno = '{$retorno}'";
                                        mysqli_query($con, $q);

                                        mysqli_query($con, "update vendas set
                                                                    operadora_id = '{$operadora_id}',
                                                                    forma_pagamento = '{$forma_pagamento}',
                                                                    operadora = 'mercadopago',
                                                                    operadora_situacao = '{$operadora_situacao}',
                                                                    operadora_retorno = '{$retorno}'
                                                            where codigo = '{$d->codigo}'
                                                    ");

                                    }
                                }

                                // $qrcode = '12e44a26-e3b4-445f-a799-1199df32fa1e';
                                // $operadora_id = 23997683882;

                            ?>
                            Utilize o QrCode para pagar a sua conta ou copie o códio PIX abaixo.
                        </p>
                        <div style="padding:20px;">
                            <img src="data:image/png;base64,<?=$qrcode_img?>" style="width:100%">
                            <!-- <img src="img/qrteste.png" style="width:100%"> -->
                            <div class="status_pagamento"></div>
                        </div>
                        Total a Pagar:
                        <h1>R$ <?=number_format($d->total,2,',','.')?></h1>
                        <p style="text-align:center; font-size:12px;">Clique no botão abaixo para copiar o Código PIX de sua compra.</p>
                        <!-- <p style="text-align:center; font-size:16px;"><?=$qrcode?></p> -->
                        <button copiar="<?=$qrcode?>" class="btn btn-secondary btn-lg btn-block"><i class="fa-solid fa-copy"></i> <span>Copiar Código PIX</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>



    $(function(){

        CopyMemory = function (text) {

             var $txt = $('<textarea />');
            $txt.val(text).css({ width: "500px", height: "500px", position:'fixed', left:10, top: 10}).appendTo(".status_pagamento");

            $txt.select();

            if(document.execCommand('copy')){
                //  $.alert($txt.val())
                // $txt.remove();
            }else{
                $.alert('nada');
            }
            // alert('acesso');
        }

        $("button[copiar]").click(function(){
            obj = $(this);
            texto = $(this).attr("copiar");
            CopyMemory(texto);
            obj.removeClass('btn-secondary');
            obj.addClass('btn-success');
            obj.children("span").text("Código PIX Copiado!");
        });

        <?php
        if($operadora_id){
        ?>
        $.ajax({
            url:"src/pagar_pix_verificar.php?convidado=<?=$_SESSION['convidado']?>",
            type:"POST",
            data:{
                id:'<?=$operadora_id?>'
            },
            success:function(dados){
                $(".status_pagamento").html(dados)
            }
        });
        <?php
        }
        ?>
    })
</script>