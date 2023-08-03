<?php
    include("../../../lib/includes.php");

    if($_POST['acao'] == 'acrescimo' or $_POST['acao'] == 'desconto'){
        $q = "update vendas set {$_POST['acao']} = '{$_POST['valor']}' where codigo = '{$_SESSION['AppVenda']}'";
        mysqli_query($con, $q);
        // exit();
    }

    VerificarVendaApp();

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
    taxa='".($c->total/100*10)."',
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
                <div class="card-header">Dados da Compra</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Mesa</small>
                                <div style="font-size:18px !important; color:blue;"><?=$m->mesa?></div>
                            </h5>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Valor da compra</small>
                                <div style="font-size:18px !important; color:blue;"><?=$d->valor?></div>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Taxa de Serviços</small>
                                <div><?="{$d->taxa}"?></div>
                            </h5>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Valor da compra</small>
                                <!-- <div class="valor" valor="<?=$d->valor?>">R$ <?=number_format($d->valor,2,'.',false)?></div> -->
                                <div class="valor_pendente" pendente="" valor=""><?=$d->total?></div>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">Escolha a forma de pagamento</div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button
                                type="button"
                                class="adicionarPagamento btn btn-primary btn-lg btn-block"
                            >PIX</button>
                        </div>
                        <div class="col">
                            <button
                                type="button"
                                class="adicionarPagamento btn btn-primary btn-lg btn-block"
                            >CRÉDITO</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="formas_pagamento row">
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Formas de Pagamento</div>
                <div pagar_operacoes class="card-body">


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

        $('.money').maskMoney();
        $('.money').click(function(){
            $(this).val('0.00');
        });

        if(terminal){
            $('.money').keyboard({type:'numpad'});;
        }


        $.ajax({
            url:"src/produtos/pagar_operacoes.php",
            success:function(dados){
                $("div[pagar_operacoes]").html(dados);
            }
        });


        $("a[pagamento]").click(function(){
            opc = $(this).attr("pagamento");
            titulo = $(this).html();
            $(".operacao").val(opc);
            $(".titulo_pagamento").html(titulo);
        });


        CalculoDesconto = (obj, opc)=>{
            Carregando();
            pendente = $(".valor_pendente").attr("pendente");
            valor = ((opc == 1)? obj.val() : 0);
            valor_oposto = 0; //$('input[calc="acrescimo"]').val();

            if(valor*1 > pendente*1){
                $.alert('Valor do desconto não pode ser superior ao valor pendente!');
                $('input[calc="desconto"]').val('0.00');
                Carregando('none');
                return false;
            }

            // valor_pendente = (pendente*1 - valor*1 + valor_oposto*1);
            // $(".valor_pendente").attr("valor", valor_pendente.toFixed(2));
            // // $(".valor_pendente").html('R$ ' + valor_pendente.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            // $(".valor_pendente").html('R$ ' + valor_pendente.toFixed(2));
            // $(".UmPagamento").val(valor_pendente.toFixed(2));

            $.ajax({
                url: "componentes/ms_popup_100.php",
                type: "POST",
                data:{
                    acao:"desconto",
                    valor,
                    local: "src/produtos/pagar.php",
                },
                success: function (dados) {
                    PageClose();
                    $(".ms_corpo").append(dados);
                }
            });

            // $.ajax({
            //     url:"src/produtos/pagar.php",
            //     type:"POST",
            //     data:{
            //         acao:"desconto",
            //         valor
            //     },
            //     success:function(dados){
            //         //$(".valor_pendente").attr("pendente", '<?=number_format($d->total,2,'.',false)?>');
            //         PageClose();
            //         $(".ms_corpo").append(dados);
            //         // $.ajax({
            //         //     url:"src/produtos/pagar_operacoes.php",
            //         //     type:"POST",
            //         //     success:function(dados){
            //         //         $("div[pagar_operacoes]").html(dados);
            //         //     }
            //         // });


            //     }
            // });
        }


        $('input[calc="desconto"]').blur(function(){
            CalculoDesconto($(this), 1);
        });

        $('#MarcarTaxa').click(function(){
            var opc;
            if($(this).prop("checked") == true){
                opc = 1;
            }else{
                opc = 0;
            }
            CalculoDesconto($(this), opc);
        });

        $('input[calc="acrescimo"]').blur(function(){
            Carregando();
            pendente = $(".valor_pendente").attr("pendente");
            valor = $(this).val();
            valor_oposto = 0; //$('input[calc="desconto"]').val();

            if(valor*1 < 0){
                $.alert('Valor do acrescimo não pode ser negativo!');
                $('input[calc="acrescimo"]').val('0.00');
                Carregando('none');
                return false;
            }
            valor_pendente = (pendente*1 + valor*1 - valor_oposto*1);

            $(".valor_pendente").attr("valor", valor_pendente.toFixed(2));
            // $(".valor_pendente").html('R$ ' + valor_pendente.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            $(".valor_pendente").html('R$ ' + valor_pendente.toFixed(2));

            $(".UmPagamento").val(valor_pendente.toFixed(2));

            $.ajax({
                url:"src/produtos/pagar.php",
                type:"POST",
                data:{
                    acao:"acrescimo",
                    valor
                },
                success:function(dados){
                    //$(".valor_pendente").attr("pendente", '<?=number_format($d->total,2,'.',false)?>');
                    $.ajax({
                        url:"src/produtos/pagar_operacoes.php",
                        type:"POST",
                        success:function(dados){
                            $("div[pagar_operacoes]").html(dados);
                        },
                        error:function(){
                            $.alert('Erro');
                            Carregando('none');
                        }
                    });
                }
            });


        });


        $(".valor_pendente").click(function(){
            valor = $(this).attr("valor");
            $(".UmPagamento").val(valor);
        });

        $(".adicionarPagamento").click(function(){

            valor_pendente = $(".valor_pendente").attr('valor');
            operacao = $(".operacao").val();
            valor = $(".UmPagamento").val();

            if(!operacao || !valor){
                $.alert('Favor definir a operação do pagamento!');
            }else if(valor_pendente == 0){
                $.alert('Não existe valor pendente para pagamento!');
            }else if(valor*1 > valor_pendente*1){
                $.alert('O valor informado não pode ser maior que o valor pendente!');
            }else{
                $.ajax({
                    url:"src/produtos/pagar_operacoes.php",
                    type:"POST",
                    data:{
                        operacao,
                        valor,
                        acao:'nova_operacao'
                    },
                    success:function(dados){
                        $("div[pagar_operacoes]").html(dados);
                    }
                });
            }

        });

    })
</script>