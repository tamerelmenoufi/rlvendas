<?php

include("../../lib/includes.php");
include "./conf.php";

?>
<style>
    .fechar{
        position:absolute;
        top:5px;
        right:5px;
        cursor: pointer;
        color:red;
        font-size:25px;
    }
</style>

<div>
    <i class="fa-solid fa-rectangle-xmark fechar"></i>
</div>



<script>
    $(function () {

        $(".fechar").off('click').on('click', function(){
            $(".TelaVendas").css("display","none");
        });

    });
</script>