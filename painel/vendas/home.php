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
        height:90px;
        background:green;
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
    .bk_categoria_scroll_card {
        min-width: 80px;
        height: 100px;
        text-align: center;
        border:0;
        background:transparent;
        margin:5px;
    }
    .bk_categoria_scroll_card div{
        position:relative;
        width:80px;
        height:80px;
        background-color: #EBF4F1;
        border-radius:10px;
        float:none;
        text-align:center;
    }
    .bk_categoria_scroll_card p{
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
        display: hidden;
    }

</style>

<div>
    <!-- Apenas o botão de fechar -->
    <i class="fa-solid fa-rectangle-xmark fecharTelaVendas"></i>

    <!-- Lista das categorias a serem exibidas -->
    <div class="bk_categoria_scroll_palco">
        <div class="bk_categoria_scroll">
        <?php
            for($i=1;$i<50;$i++){
        ?>
            <div opc="<?=$i?>" categoria="<?=$i?>" class="bk_categoria_scroll_card">
                <div>
                    <img src="svg/frutas.svg" style="margin-top:15px;" />
                </div>
                <p>Eletrodomésticos</p>
            </div>
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