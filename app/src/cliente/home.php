<?php
    include("../../../lib/includes.php");


    if($_POST['acao'] == 'Sair'){
        $_SESSION = [];
        exit();
    }

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
        <button sair class="btn btn-danger btn-lg btn-block">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
            Desconectar
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




        $("button[sair]").click(function(){
            $.confirm({
                content:"Deseja realmente Sair do aplicativo?",
                title:false,
                buttons:{
                    'SIM':function(){

                        $.ajax({
                            url:"src/cliente/home.php",
                            type:"POST",
                            data:{
                                acao:'Sair',
                            },
                            success:function(dados){
                                window.localStorage.removeItem('AppPedido');
                                window.localStorage.removeItem('AppCliente');
                                window.localStorage.removeItem('AppVenda');

                                $.ajax({
                                    url:"src/home/index.php",
                                    success:function(dados){
                                        $(".ms_corpo").html(dados);
                                    }
                                });

                            }
                        });

                    },
                    'NÃO':function(){

                    }
                }
            });


        });

    })
</script>