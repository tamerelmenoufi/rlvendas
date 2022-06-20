<?php
    include("../../../lib/includes.php");

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
    .valor_pendente{
        color:red;
        font-size:14px;
        cursor:pointer;
    }
    .formas_pagamento{
        display:none;
    }
</style>
<div class="PedidoTopoTitulo">
    <h4>Pagar Mesa <?=$m->mesa?></h4>
</div>

<div class="col" style="margin-bottom:60px; display:<?=(($d->total)?'block':'none')?>">
    <div class="row">
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Dados da Compra</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Pedido</small>
                                <div style="font-size:18px; color:blue;"><?=$_SESSION['AppPedido']?></div>
                            </h5>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title">
                                <small>Valor</small>
                                <div style="font-size:20px; color:green;" class="valor" valor="<?=$d->valor?>">R$ <?=number_format($d->valor,2,',','.')?></div>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="card-title">
                                <small>Mesa</small>
                                <div><?="{$m->mesa}"?></div>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <!-- <div class="form-check">
                                <input type="checkbox" class="form-check-input" calc="TaxaServico" id="MarcarTaxa" value="<?=$d->taxa?>" <?=(($d->taxa > 0)?'checked':false)?>>
                                <label class="form-check-label" for="MarcarTaxa">Taxa de Serviço <b><?=number_format($d->taxa, 2,',','.')?></b> (Opcional)</label>
                            </div> -->
                            <h5 class="card-title">
                                <small>Taxa de Serviço <?=number_format($d->taxa, 2,',','.')?> (Opcional)</small>
                                <input calc="TaxaServico" type="hidden" value="<?=$d->taxa?>">
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <h5 class="card-title">
                                <small>Acréscimo</small>
                                <div>
                                    <input calc="acrescimo" class="form-control form-control-sm money" type="text" value="<?=$d->acrescimo?>" data-thousands="" data-decimal=".">
                                </div>
                            </h5>
                        </div>

                        <div class="col">
                            <h5 class="card-title">
                                <small>Desconto</small>
                                <div>
                                    <input calc="desconto" class="form-control form-control-sm money" type="text" value="<?=$d->desconto?>" data-thousands="" data-decimal=".">
                                </div>
                            </h5>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-12">
                            <h5 class="card-title">
                                <small>Valor Pendente</small>
                                <div class="valor_pendente" valor=""></div>
                            </h5>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <h5 class="card-title">
                                <small>Operação</small>
                                <div>
                                    <select class="operacao form-control form-control-sm">
                                        <option value="">::Selecione::</option>
                                <?php
                                    $qf = "select * from pagamentos where deletado != '1'";
                                    $rf = mysqli_query($con, $qf);
                                    while($f = mysqli_fetch_object($rf)){
                                ?>
                                        <option value="<?=$f->pagamento?>"><?=strtoupper($f->pagamento)?></option>
                                <?php
                                    }
                                ?>
                                    </select>
                                </div>
                            </h5>
                        </div>

                        <div class="col">
                            <h5 class="card-title">
                                <small>Valor</small>
                                <div>
                                <input class="form-control form-control-sm UmPagamento" type="text" value="<?=$d->total?>">
                                </div>
                            </h5>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <button
                                type="button"
                                class="adicionarPagamento btn btn-primary btn-sm btn-block"
                            >Adicionar</button>
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

        $.ajax({
            url:"src/produtos/pagar_operacoes.php",
            success:function(dados){
                $("div[pagar_operacoes]").html(dados);
            }
        });

        $('input[calc="desconto"]').keyup(function(){
            pendente = $(".valor_pendente").attr("valor");
            valor = $(this).val();

            if(valor*1 > pendente*1){
                $.alert('Valor do desconto não pode ser superior ao valor pendente!');
                $('input[calc="desconto"]').val('0.00');
                return false;
            }
            valor_pendente = (pendente*1 - valor*1);

            $(".valor_pendente").attr("valor", valor_pendente.toFixed(2));
            $(".valor_pendente").html('R$ ' + valor_pendente.toLocaleString('pt-br', {minimumFractionDigits: 2}));

            $(".UmPagamento").val(valor_pendente.toFixed(2));

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