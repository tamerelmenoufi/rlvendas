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
        <button acao opc="definir_impressora" class="btn btn-success btn-lg btn-block">
            <i class="fa-solid fa-print"></i> Impressora <span impressora_padrao></span>
        </button>
        <button acao opc="vendas_concluidas" class="btn btn-success btn-lg btn-block" style="opacity:0">
            <i class="fa-solid fa-bell-concierge"></i> Vendas Concluídas
        </button>
    </div>
</div>

<script>
    $(function(){

        Carregando('none');
        impressora = window.localStorage.getItem('AppImpressora');

        if(impressora == null || impressora == undefined || !impressora){
            impressora = 'terminal2';
        }

        tipo_impressora = [];
        tipo_impressora['terminal1'] = 'Caixa';
        tipo_impressora['terminal2'] = 'Terminais';

        $("span[impressora_padrao]").html(tipo_impressora[impressora]);

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