<?php
    include("../../lib/includes.php");

    if($_SESSION['AppCliente']) $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));
    if($_SESSION['AppPedido']) $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}' AND deletado != '1'"));
    if($_SESSION['AppGarcom']) $g = mysqli_fetch_object(mysqli_query($con, "select * from atendentes where codigo = '{$_SESSION['AppGarcom']}' AND deletado != '1'"));
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
    i[sair]{
        margin:5px;
        color:#fff;
        cursor:pointer;
    }
</style>
<div class="row">
    <div class="col-4">
        <img class="topoImg" src="img/logo.png" />
    </div>
    <div class="col-7">
        <?php
            if($g->nome){
        ?>
            <div class="DadosTopo"><?=$g->nome?></div>
        <?php
            }
            if($m->mesa){
        ?>
            <div class="DadosTopo">Pedido Mesa <b><?=$m->mesa?></b></div>
        <?php
            }
        ?>
    </div>
    <div class="col-1">
        <i sair class="fa-solid fa-circle-xmark"></i>
    </div>
</div>


<script>
    $(function(){

        $("i[sair]").click(function(){


            $.confirm({
                icon: "fa-solid fa-right-from-bracket",
                content: false,
                title: "Deseja Realmente sair do aplicativo?",
                columnClass: "medium",
                type: "red",
                buttons: {
                    'SIM': {
                        text: "SIM",
                        action: function () {

                            window.localStorage.removeItem('AppPedido');
                            window.localStorage.removeItem('AppCliente');
                            window.localStorage.removeItem('AppVenda');
                            window.localStorage.removeItem('AppGarcom');

                            $.ajax({
                                url:"src/home/index.php",
                                type:"POST",
                                data:{
                                    acao:'Sair',
                                    confirm:'1',
                                },
                                success:function(dados){
                                    PageClose();
                                    window.location.href='./?s=1';
                                }
                            });


                        }
                    },
                    'Não': {
                        text: "NÃO",
                        action: function () {
                            PageClose();
                        }
                    }
                }
            })

        })

    })
</script>