<?php
    include("../../../lib/includes.php");

?>
<style>
    .ClienteTopoTitulo{
        position:relative;
        width:100%;
        text-align:center;
    }
</style>

<div class="ClienteTopoTitulo">
    <h4>
        <i class="fa-solid fa-user"></i> Sobre o Cliente
    </h4>
</div>

<div class="col">
    <div class="col-12">
        <button acao opc="perfil" class="btn btn-success btn-lg btn-block">
            <i class="fa-solid fa-user-pen"></i> Perfil pessoal
        </button>
        <button class="btn btn-success btn-lg btn-block">
            <i class="fa-solid fa-bell-concierge"></i> Meus Pedidos
        </button>
        <button class="btn btn-success btn-lg btn-block">
            <i class="fa-solid fa-envelope"></i> Fale Conosco
        </button>
        <button acao opc="senha" class="btn btn-success btn-lg btn-block">
            <i class="fa-solid fa-key"></i> Alterar Senha
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
                    local:`src/cliente/${local}.php`,
                },
                success:function(dados){
                    //PageClose();
                    $(".ms_corpo").append(dados);
                }
            });
        });
    })
</script>