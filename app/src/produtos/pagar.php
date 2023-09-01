<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'acrescimo' or $_POST['acao'] == 'desconto'){
        $q = "update vendas set {$_POST['acao']} = '{$_POST['valor']}' where codigo = '{$_SESSION['AppVenda']}'";
        mysqli_query($con, $q);
        // exit();
    }

    VerificarVendaApp('mesa');

    if($_SESSION['AppPedido']){
        $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}'"));
    }

    $query = "select
                    sum(a.valor_total) as total,
                    b.nome,
                    b.telefone
                from vendas_produtos a
                    left join clientes b on a.cliente = b.codigo
                where a.venda = '{$_SESSION['AppVenda']}' and a.deletado != '1'";
    $result = mysqli_query($con, $query);
    $c = mysqli_fetch_object($result);

    $q = "update vendas set
    valor='{$c->total}',
    taxa='".($c->total*0/100*10)."',
    total= (".($c->total + ($c->total/100*10))." + acrescimo - desconto)
where codigo = '{$_SESSION['AppVenda']}'";
    mysqli_query($con, $q);

    $query = "select * from vendas where codigo = '{$_SESSION['AppVenda']}' and deletado != '1'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);


    if(!$d->total) $_SESSION['AppCarrinho'] = false;

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
    .card-title small{
        font-size:10px;
    }
    .card-title div{
        width:100%;
        text-align:left;
        font-size:14px;
        font-weight:bold;
    }
    .card-title a{
        width:100%;
        text-align:left;
    }

    .SemProduto{
        position:fixed;
        top:40%;
        left:0;
        text-align:center;
        width:100%;
        color:#ccc;
    }
    .icone{
        font-size:70px;
    }
    /* .valor_pendente{
        color:red;
        font-size:14px;
        cursor:pointer;
    }
    .valor{
        font-size:20px;
        color:green;
    } */
    .valor{
        color:red;
        font-size:14px;
        cursor:pointer;
    }
    .valor_pendente{
        font-size:30px !important;
        color:green;
    }

    .formas_pagamento{
        display:none;
    }
</style>
<!-- <div class="PedidoTopoTitulo">
    <h4>Pagar Mesa <?=$m->mesa?></h4>
</div> -->

<div class="col" style="margin-bottom:60px; display:<?=(($d->total)?'block':'none')?>">
    <div class="row">
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Dados da Compra - <?=$_SESSION['AppVenda']?></div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-4">
                            <h5 class="card-title">
                                <small>Valor da compra</small>
                                <div style="font-size:18px !important; color:blue;"><?=$d->valor?></div>
                            </h5>
                        </div>    
                        <div class="col-4">
                            <h5 class="card-title">
                                <small>Taxa de Serviços</small>
                                <div><?="{$d->taxa}"?></div>
                            </h5>
                        </div>                        
                        <div class="col-4">
                            <h5 class="card-title">
                                <small>Valor a pagar</small>
                                <!-- <div class="valor" valor="<?=$d->valor?>">R$ <?=number_format($d->valor,2,'.',false)?></div> -->
                                <div class="valor_pendente" pendente="" valor=""><?=$d->total?></div>
                            </h5>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                        <?php
                            $valor_pago = 0;
                            $query = "select * from status_venda where venda = '{$d->codigo}' where retorno->>'$.status' = 'approved'";
                            $result = mysqli_query($con, $query);
                            while($p = mysqli_fetch_object($result)){
                            $op = json_decode($p->retorno);
                            $valor_pago = ($valor_pago + $op->transaction_amount);
                        ?>
                        <p>
                            Forma de Pagamento: <?=$p->forma_pagamento?><br>
                            Situação: <?=$op->status?><br>
                            Valor: <?=number_format($op->transaction_amount,2,',','.')?>
                        </p>
                        <?php

                        }
                        ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">Escolha a forma de pagamento</div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button
                                pagamento="pix"
                                type="button"
                                class="adicionarPagamento btn btn-primary btn-lg btn-block"
                            >
                                <i class="fa fa-qrcode fa-3x"></i><br>
                                R$ <?=number_format(($d->total - $valor_pago),2,',','.')?><br>PIX
                            </button>
                        </div>
                        <!-- <div class="col">
                            <button
                                pagamento="credito"
                                type="button"
                                class="adicionarPagamento btn btn-primary btn-lg btn-block"
                            >
                                <i class="fa fa-credit-card fa-3x"></i><br>
                                R$ <?=number_format(($d->total - $valor_pago),2,',','.')?><br>CRÉDITO
                            </button>
                        </div> -->
                    </div>


                </div>
            </div>
        </div>
    </div>


    <?php

$query = "select * from status_venda where venda = '{$d->codigo}'";
$result = mysqli_query($con, $query);
$n = mysqli_num_rows($result);
?>

    <div style="display:<?=(($n)?'flex':'none')?>;">
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Formas de Pagamento</div>
                <div pagar_operacoes class="card-body">

                <?php

                    $query = "select * from status_venda where venda = '{$d->codigo}' where retorno->>'$.status' = 'approved';";
                    $result = mysqli_query($con, $query);
                    while($p = mysqli_fetch_object($result)){

                        $op = json_decode($p->retorno);
                ?>
                    <p>
                        Forma de Pagamento: <?=$p->forma_pagamento?><br>
                        Situação: <?=$op->status?><br>
                        Valor: <?=number_format($op->transaction_amount,2,',','.')?>
                    </p>
                <?php

                    }
                ?>


                    <!-- <h5 class="card-title">
                        <a pagar opc="dinheiro" class="btn btn-success btn-lg"><i class="fa-solid fa-money-bill-1"></i> Dinheiro</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="pix" class="btn btn-success btn-lg"><i class="fa-brands fa-pix"></i> PIX</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="debito" class="btn btn-success btn-lg"><i class="fa-solid fa-credit-card"></i> Débito</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="credito" class="btn btn-success btn-lg"><i class="fa-solid fa-credit-card"></i> Crédito</a>
                    </h5> -->

                </div>
            </div>
        </div>
    </div>



</div>


<div class="SemProduto" style="display:<?=(($d->total)?'none':'block')?>">
    <i class="fa-solid fa-face-frown icone"></i>
    <p>Poxa, ainda não tem produtos em seu pedido!</p>
</div>


<script>
    $(function(){



        $("button[pagamento]").click(function(){

            opc = $(this).attr("pagamento");
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:`src/produtos/pagar_${opc}.php`,
                },
                success:function(dados){
                    PageClose();
                    $(".ms_corpo").append(dados);
                }
            });


        });



    })
</script>