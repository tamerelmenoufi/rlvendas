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


    #mais{
        position:absolute;
        bottom:0;
        width:40px;
        left:100px;
    }
    #quantidade{
        position:absolute;
        bottom:0;
        width:50px;
        left:50px;
        border:0;
    }
    #menos{
        position:absolute;
        bottom:0;
        width:40px;
        left:10px;
    }

    #rotulo_valor{
        position:absolute;
        right:10px;
        bottom:0px;
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
        <div class="card mb-3" style="padding-bottom:60px;">
            <div class="card-body">
            <h5 class="card-title" style="paddig:0; margin:0; font-size:14px; font-weight:bold;">
                <?=$pedido->categoria->descricao?>
                - <?=$pedido->medida->descricao?>
            </h5>
            <p class="card-text" style="padding:0; margin:0;">
                <?php
                    $ListaPedido = [];
                    for($i=0; $i < count($pedido->produtos); $i++){
                        $ListaPedido[] = $pedido->produtos[$i]->descricao;
                    }
                ?>
            </p>
            <p class="card-text" style="padding:0; margin:0;">
                <small class="text-muted">
                <?php
                    if($ListaPedido) echo implode(', ', $ListaPedido);
                ?>
                </small>
            </p>

                <div style="position:absolute; bottom:0px; left:0px; width:100%; border:solid 1px red;">



                        <button
                                class="btn text-danger"
                                type="button"
                                id="menos"
                        >
                            <i class="fa-solid fa-circle-minus"></i>
                        </button>

                        <div
                                class="form-control"
                                id="quantidade"
                        >1</div>

                        <button
                                class="btn text-success"
                                type="button"
                                id="mais">
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>

                        <span
                                class="btn text-primary"
                                id="rotulo_valor">
                            R$ <span valor atual="<?=$_POST['valor']?>">
                                <?= number_format($_POST['valor'], 2, ',', '.') ?>
                            </span>
                        </span>

                    </div>


                </div>

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