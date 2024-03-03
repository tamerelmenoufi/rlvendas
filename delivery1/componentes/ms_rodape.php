<?php
    include("../../lib/includes.php");

    if($_SESSION['AppCliente']) $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_SESSION['AppCliente']}'"));
    if($_SESSION['AppPedido']) $m = mysqli_fetch_object(mysqli_query($con, "select * from mesas where codigo = '{$_SESSION['AppPedido']}' AND deletado != '1'"));


?>
<style>
    .rodape{
        position:fixed;
        width:100%;
        height:60px;
        background-color:#c0941f;
        left:0;
        bottom:0;
    }
    .rodape .row .col {
        color:#fff;
        text-align:center;
        font-size:30px;
        padding:0;
        margin:0;
    }
    .user{
        color:#fff;
        text-align:center;
        font-size:35px;
        padding:10px;
        margin:0px;
        margin-right:10px;
    }
    .rodape .row .col p{
        font-size:10px;
        text-align:center;
        color:#fff;
        padding:0;
        margin:0;
    }
    .user div{
        font-size:12px;
        text-align:left;
        color:#fff;
        padding-left:10px;
        padding-top:0px;
        margin:0;
    }
</style>
<div class="rodape">
<div class="row">

    <div class="col-7 acao" componente="ms_popup" local="src/cliente/home.php">
        <div class="d-flex justify-content-start user">
            <i class="fa-solid fa-circle-user"></i><div><span ClienteNomeApp><?=explode(" ",trim($c->nome))[0]?></span> <i class="fa-solid fa-pencil"></i><br><?=$c->telefone?></div>
        </div>
    </div>
    <div class="col acao" componente="ms_popup_100" local="src/produtos/pedido.php"><i class="fa-solid fa-bell-concierge"></i><p>Pedido</p></div>
    <div class="col acao" componente="ms_popup_100" local="src/home/contato.php"><i class="fa-solid fa-headset"></i><p>Contato</p></div>
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
</div>
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