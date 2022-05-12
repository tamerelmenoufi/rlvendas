<?php

include("../../lib/includes.php");
include "./conf.php";


// $_SESSION['PainelVenda'] = $codigo;
// $_SESSION['PainelCliente'] = $cliente;
// $_SESSION['PainelPedido'] = $mesa;
// $_SESSION['PainelGarcom'] = $garcom;


?>
<style>
    .fecharTelaVendas{
        position:absolute;
        top:20px;
        right:20px;
        cursor: pointer;
        color:red;
        font-size:50px;
    }


    .bk_categoria_scroll_palco {
        overflow-x: auto;
        position:absolute;
        top:80px;
        left:0;
        right:0;
        height:120px;
    }
    .bk_categoria_scroll {
        display: flex;
        flex-direction: row;
        justify-content: left;
        align-items: left;
        width: 100%;
        padding:0px;
        overflow:scroll;
    }


    .bk_categoria_scroll button{
        position:relative;
        width:120px;
        height:80px;
        border-radius:10px;
        float:none;
        text-align:center;
        font-size:15px;
        margin:5px;
    }

    .bk_categoria_scroll div{
        position:relative;
        width:80px;
        height:80px;
        background-color: #EBF4F1;
        border-radius:10px;
        float:none;
        text-align:center;
    }
    .bk_categoria_scroll p{
        position:relative;
        width:80px;
        height:auto;
        color:#9C9C9C;
        font-family:Raleway;
        font-style: normal;
        font-size: 12px;
        line-height: 14px;
        text-align:center;
        margin-top:5px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;

    }
    .bk_categoria_scroll::-webkit-scrollbar {
        display: none;
    }

</style>

<div id="CorpoTelaVendas">
    <!-- Apenas o botão de fechar -->
    <i class="fa-solid fa-rectangle-xmark fecharTelaVendas"></i>

    <!-- Tela dos produtos filtrados -->

    <!-- Tela da comanda da mesa -->

    <!-- Tela das ações -->

</div>




<script>
    $(function () {


        <?php
        if(!$_SESSION['PainelGarcom']){
        ?>

            $.dialog({
                title:false,
                content:"url:vendas/garcom/login.php",
                columnClass:'col-md-4'
            });

        <?php
        }else{
        ?>

        $.ajax({
            url:"vendas/telas/categorias.php",
            success:function(dados){
                $("#CorpoTelaVendas").append(dados);
            }
        });

        $.ajax({
            url:"vendas/telas/comanda.php",
            success:function(dados){
                $("#CorpoTelaVendas").append(dados);
            }
        });

        <?php
        }
        ?>
    });
</script>