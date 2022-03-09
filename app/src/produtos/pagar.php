<?php
    include("../../../lib/includes.php");

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
</style>
<div class="PedidoTopoTitulo">
    <h4>Pagar <?=$_SESSION['AppPedido']?></h4>
</div>
<div class="col" style="margin-bottom:60px;">

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
                                <div style="font-size:20px; color:green;">R$ <?=number_format($d->total,2,',','.')?></div>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5 class="card-title">
                                <small>Cliente</small>
                                <div><?="{$d->nome} {$d->telefone}"?></div>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card bg-light mb-3">
                <div class="card-header">Formas de Pagamento</div>
                <div class="card-body">
                    <h5 class="card-title">
                        <a pagar opc="debito" class="btn btn-success btn-lg"><i class="fa-solid fa-credit-card"></i> Débito</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="credito" class="btn btn-success btn-lg"><i class="fa-solid fa-credit-card"></i> Crédito</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="pix" class="btn btn-success btn-lg"><i class="fa-brands fa-pix"></i> PIX</a>
                    </h5>
                    <h5 class="card-title">
                        <a pagar opc="dinheiro" class="btn btn-success btn-lg"><i class="fa-solid fa-money-bill-1"></i> Dinheiro</a>
                    </h5>
                </div>
            </div>
        </div>
    </div>



</div>

<script>
    $(function(){

        $("a[pagar]").click(function(){
            opc = $(this).attr("opc");
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:'src/produtos/pagar_'+opc+'.php',
                },
                success:function(dados){
                    //PageClose();
                    $(".ms_corpo").append(dados);
                }
            });
        });


    })
</script>