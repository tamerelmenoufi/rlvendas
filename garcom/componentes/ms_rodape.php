<?php
    include("../../lib/includes.php");

    if($_SESSION['AppCliente']) $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));
    if($_SESSION['AppPedido']) $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}' AND deletado != '1'"));


?>

<div class="row">
    <div class="col acao" componente="ms_popup_100" local="src/mesas/home.php"><i class="fa-solid fa-circle-user"></i><p>Mesas</p></div>
    <div class="col acao" componente="ms_popup" local="src/mesas/opcoes.php"><i class="fa-solid fa-gear"></i><p>Ações</p></div>
    <div class="col acao" componente="ms_popup_100" local="src/produtos/pedido.php"><i class="fa-solid fa-bell-concierge"></i><p>Pedido</p></div>
    <div class="col acao" componente="ms_popup_100" local="src/produtos/pagar.php"><i class="fa-solid fa-circle-dollar-to-slot"></i><p>Pagar</p></div>
</div>
<script>
    ///////////////
    $(function(){

        $(".acao").click(function(){

            AppPedido = window.localStorage.getItem('AppPedido');
            AppCliente = window.localStorage.getItem('AppCliente');
            componente = $(this).attr("componente");
            local = $(this).attr("local");
            if(AppCliente && local == 'src/mesas/home.php'){
                Carregando();
                $.ajax({
                    url:"componentes/"+componente+".php",
                    type:"POST",
                    data:{
                        local,
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }else if(!AppCliente && local == 'src/mesas/home.php'){
                Carregando();
                $.ajax({
                    url:"componentes/ms_popup_100.php",
                    type:"POST",
                    data:{
                        local:'src/mesas/home.php',
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }else if(AppPedido && local){
                Carregando();
                $.ajax({
                    url:"componentes/"+componente+".php",
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