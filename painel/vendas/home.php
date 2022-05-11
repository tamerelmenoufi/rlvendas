<?php

include("../../lib/includes.php");
include "./conf.php";

?>
<style>
    .fecharTelaVendas{
        position:absolute;
        top:5px;
        right:5px;
        cursor: pointer;
        color:red;
        font-size:25px;
    }


    .bk_categoria_scroll_palco {
        overflow-x: auto;
        position:absolute;
        top:40px;
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

<div>
    <!-- Apenas o botão de fechar -->
    <i class="fa-solid fa-rectangle-xmark fecharTelaVendas"></i>

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
            acao<?=$md5?>
            local="src/produtos/produtos.php?categoria=<?=$d->codigo?>"
            janela="ms_popup_100"
    >
        <?=$d->categoria?>
    </button>
<?php
    }
?>


        </div>
    </div>
    <!-- Fim das Categorias -->

</div>




<script>
    $(function () {



    });
</script>