<?php
include("../lib/includes/includes.php");
?>
<style>
    .ms_card_produtos span {
        position: absolute;
        right: 0px;
        bottom: 0;
    }

    /* .ms_card_produtos {
        padding: 5px;
    } */

    .ms_card_produtos div {
        position: relative;
        height: 160px;
        width: 100px;
        background-color: #EBF4F1;
        border-radius: 20px;
        float: none;
        text-align: center;
        margin-left: 10px;
    }

    .ms_card_produtos_img {
        margin-top: 15px;
        width: 50px;
        float: left;
        margin-right: 10px;
    }

    .ms_card_produtos h6 {
        position: absolute;
        /* left: 11.41%; */
        /* right: 57.72%; */
        /* top: 70.7%; */
        bottom: 60px;
        font-family: Raleway;
        font-style: normal;
        font-weight: bold;
        font-size: 14px;
        line-height: 16px;
        color: #194B38;
        text-align: center;
    }

    .ms_card_produtos_price {
        display: flex;
        flex-direction: row;
        align-items: flex-end;
        padding: 0px;

        position: absolute;
        width: 55px;
        height: 22px;
        left: 17px;
        top: 95px;
    }
</style>
<div class="w3-col s12 ms_card_produtos">
    <div class="w3-padding">
        <img atua src="svg/frutas.svg" class="ms_card_produtos_img" />
        <h6>Abacate</h6>
        <img src="svg/price.svg" class="ms_card_produtos_price">
        <span>
            <img src="svg/botao_mais_right.svg" 
            class="incluir" 
            local="src/categorias/incluir.php" 
            componente="ms_popup" />
        </span>
    </div>
</div>
<script>
    $(function() {
        $(".incluir").click(function() {
            local = $(this).attr('local');
            componente = $(this).attr('componente');
            $.ajax({
                url: "componentes/" + componente + ".php",
                type: "POST",
                data: {
                    local
                },
                success: function(dados) {
                    $(".ms_corpo").append(dados);
                }
            });
        });
    })
</script>