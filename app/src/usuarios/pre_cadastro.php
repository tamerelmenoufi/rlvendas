<?php
    include("../../../../lib/includes.php");

    if($_POST['sair']){
        $_SESSION['ms_cli_codigo'] = false;
    }

    if($_POST['numero']){
        $d = mysql_fetch_object(mysql_query("select * from clientes where cli_celular = '{$_POST['numero']}'"));
        if($d->codigo){
            echo $d->codigo;
        }else{
            mysql_query("insert into clientes set cli_celular = '{$_POST['numero']}'");
            echo $novo = mysql_insert_id();
            $_SESSION['ms_cli_codigo'] = $novo;
        }
        exit();
    }

?>
<style>
.ms_pre_cadastro-titulo{
    text-align:center;

}

.ms_pre_cadastro-espaco{
    padding:10px

}

.ms_pre_cadastro-informativo{
    font-size: 13px;
    font-style: italic;
    color:#159632ee;
    text-align:center;

}

</style>


<h3 class="ms_pre_cadastro-titulo">Pré-Cadastro</h3>

<div class="ms_pre_cadastro-espaco">

<p class="ms_pre_cadastro-informativo">“Informe seu número de telefone para dar inicio ao seu cadastro!”</p>

<input type="text" placeholder="...Digite seu número..." class="form-control" id="numero_pre_cadastro" />

<button style="margin-top:7px" salvar_pre_cadastro class="btn btn-success btn-block">CADASTRAR</button>

</div>


<script>
    $(function(){
        Carregando('none');
        $("#numero_pre_cadastro").mask("(99) 99999-9999");
        $("button[salvar_pre_cadastro]").off('click').click(function(){

            numero = $("#numero_pre_cadastro").val();
            if(numero){
                $.confirm({
                    content:"<center>Você informou o número <br><b>"+numero+"</b><br>A chave da validação e ativação de seu cadastro será enviada para o seu WhatsApp.<br><br>Confirma o número ?</center>",
                    title:false,
                    buttons:{
                        'NÃO VOU CORRIGIR':function(){

                        },
                        'SIM':function(){
                            $.ajax({
                                url:"src/usuarios/pre_cadastro.php",
                                type:"POST",
                                data:{
                                    numero,
                                },
                                success:function(dados){
                                    window.localStorage.setItem('ms_cli_codigo',dados);
                                    ms_cli_codigo = dados;
                                    $.ajax({
                                        url:"src/usuarios/home.php",
                                        success:function(dados){
                                            $("div[tela_perfil]").html(dados);
                                            AppComponentes('home');
                                        }
                                    });
                                }
                            });

                        }

                    }
                });
            }else{
                $.alert('Favor preencha o número completo!');
            }

        });


    })
</script>