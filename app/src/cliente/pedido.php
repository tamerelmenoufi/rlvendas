<?php
    include("../../../lib/includes.php");
    if($_POST['SairPedido']){
        $_SESSION = [];
        exit();
    }
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
    .PedidoBottomFixo{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        background:#fff;
    }
    .PedidoBottomItens{
        padding:10px;
        text-align:center;
    }
    .PedidoBottomItens button{
        width:calc(100% - 25px);
    }

</style>
<div class="PedidoTopoTitulo">
    <h4>Pedido <?=$_SESSION['AppPedido']?></h4>
</div>
<div class="col" style="margin-bottom:60px;">
    <div class="col-12">
        <?php
            $query = "select * from vendas_produtos where venda = '{$_SESSION['AppVenda']}'";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){

                $pedido = json_decode($d->produto_json);

                //print_r($pedido)

        ?>
        <div class="card mb-3">
            <div class="card-body">
            <h5 class="card-title">
                <?=$pedido->categoria->descricao?>
                - <?=$pedido->medida->descricao?>
            </h5>
            <p class="card-text">
                <?php
                    $ListaPedido = [];
                    for($i=0; $i < count($pedido->pedidos); $i++){
                        $opc = $pedido->pedidos;
                        $ListaPedido[] = $opc['descricao'];
                    }
                    if($ListaPedido) echo implode(', ', $ListaPedido);
                ?>


            </p>
            <p class="card-text">
                <small class="text-muted">
                <?php
                    if($ListaPedido) echo implode(', ', $ListaPedido);
                ?>
                </small>
            </p>
            </div>
        </div>
        <?php
            }
        ?>
    </div>
</div>

<div class="PedidoBottomFixo">
    <div class="row">
        <div class="col PedidoBottomItens">
            <button class="btn btn-success" pagar>Pagar</button>
        </div>
        <div class="col PedidoBottomItens">
            <button class="btn btn-danger" SairPedido>Cancelar</button>
        </div>
    </div>
</div>


<script>
    $(function(){

        $("button[pagar]").click(function(){
            PageClose();
        });

        $("button[SairPedido]").click(function(){
            $.confirm({
                content:"Deseja realmente cancelar o pedido <b><?=$_SESSION['AppPedido']?></b>?",
                title:false,
                buttons:{
                    'SIM':function(){

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

                    },
                    'NÃO':function(){

                    }
                }
            });


        });
    })
</script>