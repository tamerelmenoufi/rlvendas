<?php
include("../lib/includes/includes.php");
?>
<style>
    .ms_categoria_nome {
        display: flex;
        /* width: 630px;
        height: 89px;
        left: 30px;
        top: 186px; */
        justify-content: space-between;
        align-items: center;
    }

    .ms_categoria_nome1 {
        display: flex;
        margin-top: 3px;
        align-items: center;
    }

    .ms_categoria_nome_img {
        width: 20px;
        height: 20px;
        top: 7.14%;
        margin-top: 3px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .ms_categoria_nome h4 {
        /* left: 12.06%;
        right: 42.86%;
        bottom: 10.71%; */
        margin-top: 5px;
        font-family: Raleway;
        font-style: normal;
        font-weight: bold;
        font-size: 20px;
        line-height: 23px;
        color: #194B38;
        margin-right: 20px;
    }

    .ms_categoria_nome_menu {
        position: relative;
        /* width: 20px;
        height: 20px; */
        /* left: 0px; */
        right: 60%;
        top: 7.14%;
        bottom: 17.86%;
    }
</style>

<div class="ms_categoria_nome">
    <div class="ms_categoria_nome1">
        <img class="ms_categoria_nome_img" src="svg/frutas.svg">
        <h4>Todas as Frutas</h4>
    </div>

    <div>
        <img class="ms_categoria_nome_menu" src="svg/menu_categoria.svg">
    </div>
</div>