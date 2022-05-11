<?php

include("../../../lib/includes.php");
include "../conf.php";

?>
<style>
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


    <!-- Lista das categorias a serem exibidas -->
    <div class="bk_categoria_scroll_palco">
        <div class="bk_categoria_scroll">
            <?php
                $query = "select * from categorias where deletado != '1'";
                $result = mysqli_query($con,$query);
                while($d = mysqli_fetch_object($result)){
            ?>
                <button
                        class="btn btn-success btn-lg btn-block"
                        categoria="<?=$d->codigo?>"
                >
                    <?=$d->categoria?>
                </button>
            <?php
                }
            ?>
        </div>
    </div>
    <!-- Fim das Categorias -->


<script>
    $(function () {

        $("button[categoria]").click(function(){
            categoria = $(this).attr("categoria");
            $.ajax({
                url:"vendas/telas/produtos.php",
                data:{
                    categoria
                },
                success:function(dados){
                    $(".ListaProdutosVendas").remove();
                    $("#CorpoTelaVendas").append(dados);
                }
            });
        });

    });
</script>