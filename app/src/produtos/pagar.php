<?php
    include("../../../lib/includes.php");

    $query = "select sum(valor_total) as total from vendas_produtos where venda = '{$_SESSION['AppVenda']}' and deletado != '1'";
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

</style>
<div class="PedidoTopoTitulo">
    <h4>Pagar <?=$_SESSION['AppPedido']?></h4>
</div>
<div class="col" style="margin-bottom:60px; margin-top:20px;">
    <div class="col-12">
        Valor total da compra R$ <?=number_format($d->total,2,',','.')?>
    </div>
</div>


<script>
    $(function(){



    })
</script>