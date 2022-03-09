<?php
    include("../../../lib/includes.php");

?>
<style>
    .ClienteTopoTitulo{
        position:relative;
        width:100%;
        text-align:center;
        padding:20px;
    }
</style>

<div class="ClienteTopoTitulo">
    <h4>Sobre o Cliente</h4>
</div>

<div class="col">
    <div class="col-12">
        <button perfil class="btn btn-success btn-lg btn-block">
            Perfil pessoal
        </button>
        <button class="btn btn-success btn-lg btn-block">
            Meus Pedidos
        </button>
        <button class="btn btn-success btn-lg btn-block">
            Fale Conosco
        </button>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');
        $("button[perfil]").click(function(){
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local:"src/cliente/perfil.php",
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                }
            });
        });
    })
</script>