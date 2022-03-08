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
    .card small{
        font-size:12px;
        text-align:left;
    }
    .card div{
        border:solid 1px #eee;
        border-radius:2px;
        background-color:#fff;
        color:#333;
        font-size:20px;
        text-align:center;
    }
</style>
<div class="PedidoTopoTitulo">
    <h4>Pagar <?=$_SESSION['AppPedido']?> com Débito</h4>
</div>
<div class="col" style="margin-bottom:60px; padding:20px;">
    <div class="row">
            <div class="col-12">
                <div class="card text-white bg-info mb-3">
                    <small>Nome</small>
                    <div>TAMER M. ELMENOUFI</div>
                    <small>Número</small>
                    <div>1144 3241 5783 3327</div>
                    <small>CCV</small>
                    <div>276</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){



    })
</script>