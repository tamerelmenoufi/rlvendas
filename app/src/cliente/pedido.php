<?php
    include("../../../lib/includes.php");
    if($_POST['SairPedido']){
        $_SESSION = [];
        exit();
    }
?>
<style>
    .PedidoBottomFixo{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        background:#fff;
    }
    .PedidoBottomItens{
        padding:10px;
    }
    .PedidoBottomItens button{
        width:calc(100% -20px);
    }

</style>
<div class="col" style="margin-bottom:60px;">
    <div class="col-12">Pedido <?=$_SESSION['AppPedido']?></div>
    <div class="col-12">
        <button SairPedido class="btn btn-danger btn-block">SAIR</button>
    </div>
</div>

<div class="PedidoBottomFixo">
    <div class="row">
        <div class="col PedidoBottomItens">
            <button class="btn btn-success" pagar>Pagar</button>
        </div>
        <div class="col PedidoBottomItens">
            <button class="btn btn-danger" canelar>Cancelar</button>
        </div>
    </div>
</div>


<script>
    $(function(){

        $("button[canelar]").click(function(){
            PageClose();
        });

        $("button[pagar]").click(function(){
            PageClose();
        });

        $("button[SairPedido]").click(function(){

            $.ajax({
                url:"src/cliente/pedido.php",
                type:"POST",
                data:{
                    SairPedido:'1',
                },
                success:function(dados){
                    window.localStorage.removeItem('AppPedido');
                    window.localStorage.removeItem('AppCliente');

                    $.ajax({
                        url:"src/home/index.php",
                        success:function(dados){
                            $(".ms_corpo").html(dados);
                        }
                    });

                }
            });

        });
    })
</script>