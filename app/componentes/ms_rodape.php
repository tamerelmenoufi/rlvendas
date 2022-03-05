<?php
    include("../../lib/includes.php");
?>

<div class="row">
    <div class="col"><i class="fa-solid fa-circle-user"></i><p>Cliente</p></div>
    <div class="col IconPedido"><i class="fa-solid fa-bell-concierge"></i><p>Pedido <?=$_SESSION['AppPedido']?></p></div>
    <div class="col"><i class="fa-solid fa-circle-dollar-to-slot"></i><p>Pagar</p></div>
</div>
<script>
    $(function(){

        $(".IconPedido").click(function(){

            AppPedido = window.localStorage.getItem('AppPedido');

            if(AppPedido){
                $.ajax({
                    url:"componentes/ms_popup.php",
                    type:"POST",
                    data:{
                        local:"src/cliente/pedido.php",
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }
        });

    })
</script>