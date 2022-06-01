<?php
    include("../../../lib/includes.php");

?>
<style>
    .HomeTopoTitulo{
        position:relative;
        width:100%;
        text-align:center;
    }
</style>

<div class="HomeTopoTitulo">
    <h4>
        <i class="fa-solid fa-user"></i> Configurações
    </h4>
</div>

<div class="col">
    <div class="col-12">
        <button acao opc="trocar_mesa" class="btn btn-success btn-lg btn-block">
            <i class="fa-solid fa-user-pen"></i> Trocar de Mesa
        </button>
        <button class="btn btn-success btn-lg btn-block">
            <i class="fa-solid fa-bell-concierge"></i> Incluir Comandas
        </button>
    </div>
</div>

<script>
    $(function(){

        Carregando('none');
        $("button[acao]").click(function(){
            local = $(this).attr("opc");
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:`src/mesas/${local}.php`,
                },
                success:function(dados){
                    //PageClose();
                    $(".ms_corpo").append(dados);
                }
            });
        });



    })
</script>