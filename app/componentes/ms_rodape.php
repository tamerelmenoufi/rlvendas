<?php
    include("../../lib/includes.php");
?>

<div class="row">
    <div class="col acao" componente="ms_popup" local="src/cliente/home.php"><i class="fa-solid fa-circle-user"></i><p>Cliente <?=$_SESSION['AppCliente']?></p></div>
    <div class="col acao" componente="ms_popup_100" local="src/produtos/pedido.php"><i class="fa-solid fa-bell-concierge"></i><p>Pedido <?=$_SESSION['AppPedido']?></p></div>
    <div class="col acao" componente="ms_popup_100" local="src/produtos/pagar.php"><i class="fa-solid fa-circle-dollar-to-slot"></i><p>Pagar</p></div>
</div>
<script>
    $(function(){

        $(".acao").click(function(){

            AppPedido = window.localStorage.getItem('AppPedido');

            componente = $(this).attr("componente");
            local = $(this).attr("local");

            if(AppPedido && local){
                Carregando();
                $.ajax({
                    url:"src/componentes/"+componente+".php",
                    type:"POST",
                    data:{
                        local,
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }
        });

    })
</script>