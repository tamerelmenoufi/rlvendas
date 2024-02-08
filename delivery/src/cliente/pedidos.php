<?php
    include("../../../lib/includes.php");

?>
<style>
    .PedidoTopoTitulo{
        position:fixed;
        left:70px;
        top:0px;
        height:60px;
        background:#fff;
        padding-top:15px;
        z-index:1;
    }

</style>
<div class="PedidoTopoTitulo">
    <h4>Seus Pedidos</h4>
</div>
<div class="col" style="margin-bottom:60px;">
    <div class="row">
            <div class="col-12">
            <?php
                $q = "select * from vendas where 
                                                app = 'delivery' and 
                                                cliente = '{$_SESSION['AppCliente']}' and 
                                                situacao = 'pago' and deletado != '1'";
                $r = mysqli_query($con, $q);
                if($d = mysqli_fetch_object($r)){
            ?>
                <?=$d->codigo?><br>
            <?php
                }
            ?>
                Aqui a lista dos pedidos do cliente
            </div>
    </div>
</div>

<script>
    $(function(){


    })
</script>