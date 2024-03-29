<?php
    include("../../../lib/includes.php");
    // error_reporting(E_ALL);
    $query = "select
                    *
                from vendas
                where codigo = '{$_SESSION['AppVenda']}'";

    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $valor_pago = "select sum(retorno->>'$.transaction_amount') from status_venda where venda = '{$d->codigo}' and retorno->>'$.status' = 'approved'";
    list($valor_pago) = mysqli_fetch_row(mysqli_query($con, $valor_pago));

    // $pos =  strripos($d->nome, " ");

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
        margin-bottom:15px;
        width:100%;
        text-transform:uppercase;
    }
    .status_pagamento{
        width:100%;
        text-align:center;
    }
</style>
<div class="PedidoTopoTitulo">
    <h4>Pagamento PIX</h4>
</div>
<div style="margin-top:0px;">
    <div class="col">
        <div class="row">
                <div class="col-12">
                    <p style="font-size:13px; color:red; text-align:center;">ATENÇÃO: O seu pedido será preparado após <br>a confirmação do pagamento! </p>
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
                                    $d->operadora_situacao == 'pending' and 
                                    $d->total == $dados->transaction_amount
                                    ){

                                    $operadora_id = $dados->id;
                                    $forma_pagamento = $dados->payment_method_id;
                                    $operadora_situacao = $dados->status;
                                    $qrcode = $dados->point_of_interaction->transaction_data->qr_code;
                                    $qrcode_img = $dados->point_of_interaction->transaction_data->qr_code_base64;

                                }else{

                                    $q1 = "SELECT *, retorno->>'$.id' as id FROM `status_venda` where venda = '{$d->codigo}' and retorno->>'$.status' = 'pending'";
                                    $r1 = mysqli_query($con, $q1);
                                    while($d1 = mysqli_fetch_object($r1)){
                                        $PIX = new MercadoPago;
                                        $rt = $PIX->CancelarPagamento($d1->id);
                                        mysqli_query($con, "update status_venda set retorno = '{$rt}' where venda = '{$d->codigo}' and retorno->>'$.id' = '{$d1->id}'");
                                    }

                                    $PIX = new MercadoPago;
                                    // "transaction_amount": 1.00,
                                    $json = '{
                                        "transaction_amount": '.(($d->total + $d->taxa - $d->cupom_valor) - $valor_pago).',
                                        "description": "Venda '.$pedido.' - APP Yobom",
                                        "payment_method_id": "pix",
                                        "payer": {
                                            "email": "cliente@yobom.com.br",
                                            "first_name": "Tamer",
                                            "last_name": "Elmenoufi",
                                            "identification": {
                                                "type": "CPF",
                                                "number": "60110970225"
                                            },
                                            "address": {
                                                "zip_code": "69010110",
                                                "street_name": "Monsenhor Coutinho",
                                                "street_number": "600",
                                                "neighborhood": "Centro",
                                                "city": "Manaus",
                                                "federal_unit": "AM"
                                            }
                                        }
                                    }';

                                    $retorno = $PIX->Transacao($json);

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
                                        forma_pagamento = 'pix',
                                        data = NOW(),
                                        retorno = '{$retorno}'";
                                        mysqli_query($con, $q);
                                        sisLog(
                                            [
                                                'query' => $q,
                                                'file' => $_SERVER["PHP_SELF"],
                                                'sessao' => $_SESSION,
                                                'registro' => mysqli_insert_id($con)
                                            ]
                                        );

                                        $q = "update vendas set
                                                                    operadora_id = '{$operadora_id}',
                                                                    forma_pagamento = '{$forma_pagamento}',
                                                                    operadora = 'mercadopago',
                                                                    operadora_situacao = '{$operadora_situacao}',
                                                                    operadora_retorno = '{$retorno}',
                                                                    situacao = 'preparo'
                                                            where codigo = '{$d->codigo}'
                                                    ";
                                        mysqli_query($con, $q);
                                        sisLog(
                                            [
                                                'query' => $q,
                                                'file' => $_SERVER["PHP_SELF"],
                                                'sessao' => $_SESSION,
                                                'registro' => $d->codigo
                                            ]
                                        );

                                        $q = "update vendas_produtos set
                                                        situacao = 'b'
                                                where venda = '{$d->codigo}' and situacao = 'n'
                                        ";
                                        mysqli_query($con, $q);
                                        sisLog(
                                            [
                                                'query' => $q,
                                                'file' => $_SERVER["PHP_SELF"],
                                                'sessao' => $_SESSION,
                                                'registro' => $d->codigo
                                            ]
                                        );
                                    }
                                }

                            ?>
                            Utilize o QrCode para pagar a sua conta ou copie o códio PIX abaixo.
                        </p>
                        <div style="padding:20px;">
                            <img src="data:image/png;base64,<?=$qrcode_img?>" style="width:100%">
                            <!-- <img src="img/qrteste.png" style="width:100%"> -->
                            <div class="status_pagamento"></div>
                        </div>
                        Total a Pagar:
                        <h1>R$ <?=number_format(($d->total + $d->taxa - $d->cupom_valor - $valor_pago),2,',','.')?></h1>
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
            $txt.val(text).css({ width: "500px", height: "500px", position:'fixed', left:-10000, top: -10000}).appendTo(".status_pagamento");

            $txt.select();

            if(document.execCommand('copy')){
                // $.alert($txt.val())
                $txt.remove();
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
            url:"src/produtos/pagar_pix_verificar.php",
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