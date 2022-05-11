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


    .ms_categoria_scroll_palco {
        overflow-x: auto;
    }
    .ms_categoria_scroll {
        display: flex;
        flex-direction: row;
        justify-content: left;
        align-items: left;
        width: 100%;
        padding:0px;
        overflow:scroll;
    }
    .ms_categoria_scroll_card {
        min-width: 80px;
        height: 100px;
        text-align: center;
        border:0;
        background:transparent;
        margin:5px;
    }
    .ms_categoria_scroll_card div{
        position:relative;
        width:80px;
        height:80px;
        background-color: #EBF4F1;
        border-radius:10px;
        float:none;
        text-align:center;
    }
    .ms_categoria_scroll_card p{
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
    .ms_categoria_scroll::-webkit-scrollbar {
        display: none;
    }

</style>

<div>
    <i class="fa-solid fa-rectangle-xmark fecharTelaVendas"></i>

    <div class="VendasCategorias">


        <div class="ms_categoria_scroll_palco">
            <div class="ms_categoria_scroll">
            <?php
                for($i=1;$i<50;$i++){
            ?>
                <div opc="<?=$i?>" categoria="<?=$i?>" class="ms_categoria_scroll_card">
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

    </div>


</div>



<script>
    $(function () {



    });
</script>