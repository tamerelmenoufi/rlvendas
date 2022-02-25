<?php
    include("../../lib/includes/includes.php");

?>
<style>
    .ms_icone_100{
        position:relative;
        width:100%;
        height:60px;
        margin-bottom:10px;
    }
    .ms_icone_100_item{
        position:absolute;
        left:10px;
        right:10px;
        height:100%;
        background-color:#F1F3F2;
        padding-top:20px;
        padding-left:50px;
        padding-right:45px;
        border-radius:20px;
        color:#777777;
        cursor:pointer;
    }
    .ms_icone_100_icone_esquerdo{
        position:absolute;
        left:10px;
        top:15px;
        color:#32CB4B;
    }
    .ms_icone_100_icone_direito{
        position:absolute;
        right:15px;
        top:20px;
        color:#777777;
    }
</style>

<div
    acao<?=$md5?>
    local="src/usuarios/perfil.php"
    class="ms_icone_100">
    <div class="ms_icone_100_item">
        <i class="fas fa-address-book fa-2x ms_icone_100_icone_esquerdo" ></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        Perfil Pessoal
    </div>
</div>
<div
    acao<?=$md5?>
    local="src/usuarios/lista_end.php"
    class="ms_icone_100">
    <div class="ms_icone_100_item">
        <i class="fas fa-map-marker-alt fa-2x ms_icone_100_icone_esquerdo" ></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        Endereços para entregas
    </div>
</div>
<div
    acao<?=$md5?>
    local="src/usuarios/compras.php"
    class="ms_icone_100">
    <div class="ms_icone_100_item">
        <i class="fas fa-shopping-cart fa-2x ms_icone_100_icone_esquerdo" ></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        Compras Realizadas
    </div>
</div>

<div
    acao<?=$md5?>
    local="src/usuarios/lista.php"
    class="ms_icone_100">
    <div class="ms_icone_100_item">
        <i class="fas fa-clipboard-list fa-2x ms_icone_100_icone_esquerdo" ></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        Lista da próxima compra
    </div>
</div>

<div
    acao<?=$md5?>
    local="src/contatos/home.php"
    class="ms_icone_100">

    <div class="ms_icone_100_item">
        <i class="fas fa-comments fa-2x ms_icone_100_icone_esquerdo" ></i>
        <i class="fas fa-angle-right ms_icone_100_icone_direito" ></i>
        Fale Conosco
    </div>
</div>

<div
    sair<?=$md5?>
    class="ms_icone_100">
    <div class="ms_icone_100_item" style="background:transparent !important; color:red;">
        <i class="fas fa-sign-out-alt ms_icone_100_icone_esquerdo" style="color:red; margin-top:7px;"></i>
        Encerrar Sessão
    </div>
</div>


<script>
    $(function(){
        Carregando('none');
        $("div[acao<?=$md5?>]").off('click').on('click',function(){
            local = $(this).attr('local');
            Carregando();
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local,
                },
                success:function(dados){
                    $(".ms_corpo").append(dados);
                    console.log(dados);
                }
            });
        })

        $("div[sair<?=$md5?>]").off('click').on('click',function(){
            $.confirm({
                content:"Deseja realmente encerrar a sua sessão no aplicativo?",
                title:false,
                buttons:{
                    'NÃO':function(){

                    },
                    'SIM':function(){
                        window.localStorage.removeItem('ms_cli_codigo');
                        ms_cli_codigo = 0;
                        Carregando();
                        $.ajax({
                            url:"src/usuarios/pre_cadastro.php",
                            type:"POST",
                            data:{
                                sair:'1',
                            },
                            success:function(dados){
                                $("div[tela_perfil]").html(dados);
                                console.log('passou mas não achou o lugar!');
                                AppComponentes('home');
                            },
                            error:function(){
                                console.log('erro no carregamento!');
                            }
                        });
                    }
                }
            });

        })

    })
</script>