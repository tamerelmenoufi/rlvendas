<?php
    include("../../lib/includes.php");

    if($_SESSION['AppCliente']) $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));
    if($_SESSION['AppPedido']) $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}' AND deletado != '1'"));


?>

<div class="row">

    <div class="col-6 acao" componente="ms_popup" local="src/cliente/home.php">
        <div class="d-flex justify-content-start user">
            <i class="fa-solid fa-circle-user"></i><p>Cliente<span cli></span></p>
        </div>
    </div>
    <div class="col acao" componente="ms_popup" local="src/cliente/home.php"><i class="fa-solid fa-circle-user"></i><p>Cliente<span cli></span></p></div>
    <div class="col acao" componente="ms_popup_100" local="src/produtos/pedido.php"><i class="fa-solid fa-bell-concierge"></i><p>Pedido <?=str_pad($m->mesa , 3 , '0' , STR_PAD_LEFT)?></p></div>
    <!-- <div class="col acao" componente="ms_popup_100" local="src/produtos/pagar.php"><i class="fa-solid fa-circle-dollar-to-slot"></i><p>Pagar</p></div> -->
</div>
<?php
/*
?>
<div class="row mt-2">
    <div class="col-12" style="font-size:12px; text-align:center">Yobom - Sorveteria, pizzaria e restaurante - CNPJ 28.856.577/0001-19</div>
    <div class="col-12" style="font-size:12px; text-align:center">R. Bruxelas, 15 - Planalto, Manaus - AM, 69045-260, Brasil - +55 (92) 99321-6300</div>
    <!-- <div class="col acao" componente="ms_popup_100" local="src/produtos/pagar.php"><i class="fa-solid fa-circle-dollar-to-slot"></i><p>Pagar</p></div> -->
</div>
<?php
//*/
?>
<script>
    ///////////////
    $(function(){

        $(".acao").click(function(){

            AppPedido = window.localStorage.getItem('AppPedido');
            AppCliente = window.localStorage.getItem('AppCliente');
            componente = $(this).attr("componente");
            $("span[cli]").html("CLI: " + AppCliente*1)
            local = $(this).attr("local");
            if(AppCliente*1 > 0 && local == 'src/cliente/home.php'){
                // alert(1)
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
            }else if(AppCliente*1 == 0 && local){
                Carregando();
                // alert(2)
                $.ajax({
                    url:"componentes/ms_popup_100.php",
                    type:"POST",
                    data:{
                        local:'src/cliente/cadastro.php',
                    },
                    success:function(dados){
                        $(".ms_corpo").append(dados);
                    }
                });
            }else if(/*AppPedido*1 > 0 && */local){
                Carregando();
                // alert(3)
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