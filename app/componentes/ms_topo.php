<?php
    include("../../lib/includes.php");

    if($_SESSION['AppCliente']) $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));
    if($_SESSION['AppPedido']) $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}' AND deletado != '1'"));


    $query = "SELECT * FROM vendas_produtos WHERE venda = '{$_SESSION['AppVenda']}' and situacao = 'n'";
    $result = mysqli_query($con, $query);
    $pendente = mysqli_num_rows($result);

?>
<style>
    .topoImg{
        height:50px;
        margin-left:10px;
    }
    .DadosTopo{
        text-align:right;
        font-size:12px;
        padding:5px;
        margin-right:10px;
        color:#fff;
    }
    .PedidoPendentes_topo{
        position:fixed;
        top:80px;
        left:10px;
        right:10px;
        border-radius:5px;
        background-color:#fff666;
        color:#333333;
        padding:10px;
        font-size:12px;
        text-align:center;
        display:<?=(($pendente)?'block':false)?>;
        z-index:10;
    }
</style>
<div class="row">
    <div class="col-4">
        <img class="topoImg" src="img/logo.png" />
    </div>
    <div class="col-8">
        <?php
            if($c->telefone){
        ?>
            <div class="DadosTopo"><?=$c->telefone?><br><span ClienteNomeApp><?=$c->nome?></span><br>
        <?php
            }
            if($m->mesa){
        ?>
            Mesa/Pedido <b><?=str_pad($m->mesa , 3 , '0' , STR_PAD_LEFT)?></b></div>
        <?php
            }
        ?>
    </div>
</div>

<div class="PedidoPendentes_topo">
    <b>ATENÇÃO!</b><br>
    Você possui pedidos que ainda não foram autorizados para o proparo.<br>Acesse sua lista de pedido pelo ícone <b>SINO <i class="fa-solid fa-bell-concierge"></i></b> localizado no rodapé desta página para Confirmar Pedido.
        <div style="margin-top:20px;">
            <button entendi class="btn btn-warning" style="font-size:12px;">
                <i class="fa fa-thumbs-up" aria-hidden="true"></i> ok Endendi
            </button>
        </div>
</div>

<script>
    $(function(){

        $("button[entendi]").click(function(){
            $(".PedidoPendentes_topo").remove();
        });


    })
</script>