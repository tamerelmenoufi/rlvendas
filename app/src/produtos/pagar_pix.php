<?php
    include("../../../lib/includes.php");
    error_reporting(9);
    $query = "select
                    sum(a.valor_total) as total,
                    b.nome,
                    b.telefone
                from vendas_produtos a
                    left join clientes b on a.cliente = b.codigo
                where a.venda = '{$_SESSION['AppVenda']}' and a.deletado != '1'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

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
</style>
<div class="PedidoTopoTitulo">
    <h4>Pagar <?=$_SESSION['AppPedido']?> com PIX</h4>
</div>
<div class="col" style="margin-bottom:60px;">
    <div class="row">
            <div class="col-12">
                <div class="card mb-3" style="background-color:#fafcff; padding:20px;">
                    <p style="text-align:center">
                        <?php

                            $rede = new Rede;
                            // $x = $rede->Transacao('{
                            //     "capture": false,
                            //     "kind": "credit",
                            //     "reference": "pedido3",
                            //     "amount": 2099,
                            //     "installments": 2,
                            //     "cardholderName": "John Snow",
                            //     "cardNumber": "5448280000000007",
                            //     "expirationMonth": 12,
                            //     "expirationYear": 2028,
                            //     "securityCode": "235",
                            //     "softDescriptor": "string",
                            //     "subscription": false,
                            //     "origin": 1,
                            //     "distributorAffiliation": 0,
                            //     "brandTid": "string"
                            // }');

                            //////////////////////////////////////////////////////////////////
                            // echo $rede->capture('
                            //                     {
                            //                         "tid":"10012203142252512371",
                            //                         "amount":2099
                            //                     }
                            //                     ');
                            //////////////////////////////////////////////////////////////////
                            // echo    $rede->Consulta('
                            //                         {
                            //                             "reference":"pedido1"
                            //                         }
                            //                         ');
                            //////////////////////////////////////////////////////////////////
                            // echo    $rede->ConsultaTID('
                            //                         {
                            //                             "tid":"10012203142252512371"
                            //                         }
                            //                         ');
                            //////////////////////////////////////////////////////////////////
                            // echo    $rede->Cancelar('
                            //                         {
                            //                             "tid":"10012203142252512371",
                            //                             "amount":2299,
                            //                             "url":"https://moh1.com.br/rede/cancelar/callback.php"
                            //                         }
                            //                         ');
                            //////////////////////////////////////////////////////////////////
                            // echo "CancelaRefundId: <br>";
                            // echo    $rede->ConsultaRefundId('
                            //                                 {
                            //                                     "tid":"10012203142252512371",
                            //                                     "refundId":"52d7b5f1-c667-4311-80d5-cbfdd81733f5"
                            //                                 }
                            //                                 ');
                            //////////////////////////////////////////////////////////////////
                            echo "ConsultaCancelaTID - Atualizado: <br>";
                            echo    $rede->ConsultaCancelaTID('
                                                            {
                                                                "tid":"10012203142252512371"
                                                            }
                                                            ');
                        ?>
                        <br><br><br>
                        Utilize o QrCode para pagar a sua conta ou copie o códio PIX abaixo.
                    </p>
                    <div style="padding:20px;">
                        <img src="data:image/png;base64,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" style="width:100%"></i>
                    </div>
                    <p style="text-align:center; font-size:12px;">Seu Código PIX</p>
                    <p style="text-align:center; font-size:16px;">9873DKJHD87e39868885</p>
                    <button class="btn btn-success btn-lg btn-block"><i class="fa-solid fa-copy"></i> Copiar Código PIX</button>
                </div>
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



    })
</script>