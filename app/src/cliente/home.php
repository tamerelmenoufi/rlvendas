<?php
    include("../../../lib/includes.php");


    if($_POST['acao'] == 'Sair'){

        $query = "select * from vendas_produtos where venda = '{$_SESSION['AppVenda']}' and deletado != '1' and situacao = 'n'";
        $result = mysqli_query($con, $query);
        $n = mysqli_num_rows($result);

        if($n > 0 and !$_GET['confirm']){
            echo json_encode([
                "status" => "erro",
            ]);
        }else if($_GET['confirm']){
            $_SESSION = [];

        }else{
            echo json_encode([
                "status" => "sucesso",
            ]);
            $_SESSION = [];
        }
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
                            dataType: "JSON",
                            data:{
                                acao:'Sair',
                            },
                            success:function(dados){

                                if (dados.status === "erro") {

                                    $.confirm({
                                        icon: "fa-solid fa-right-from-bracket",
                                        content: false,
                                        title: "Você ainda não confirmou seus últimos pedidos para inciarmos o preparo.<br><br>Por favor escolha uma das opções:",
                                        columnClass: "medium",
                                        type: "red",
                                        buttons: {
                                            'nao': {
                                                text: "Sair mesmo!",
                                                action: function () {

                                                    window.localStorage.removeItem('AppPedido');
                                                    window.localStorage.removeItem('AppCliente');
                                                    window.localStorage.removeItem('AppVenda');

                                                    $.ajax({
                                                        url:"src/home/index.php",
                                                        type:"POST",
                                                        data:{
                                                            acao:'Sair',
                                                            confirm:'1',
                                                        },
                                                        success:function(dados){
                                                            PageClose();
                                                            window.location.href='./';
                                                        }
                                                    });


                                                }
                                            },
                                            'sim': {
                                                text: "Quero Confirmar",
                                                action: function () {
                                                    PageClose();
                                                }
                                            }
                                        }
                                    })

                                }


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