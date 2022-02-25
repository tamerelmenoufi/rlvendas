<?php
    include("../lib/includes/includes.php");
    $opc = $_GET['opc'];

    function ms_barra_fundo_fixo($ico,$rotulo,$componente=false,$local=false){

        return "
                <img
                ms_barra_fundo_icone_ativo
                src=\"svg/ico/{$ico}_branco.svg\"
                class=\"ms_barra_fundo_icone_ativo wow animate__fadeInUp\"
                data-wow-duration=\"0.4s\"
                data-wow-delay=\"0s\"
                componente=\"{$componente}\"
                local=\"{$local}\"
                />
                <img
                ms_barra_fundo_imagem
                src=\"svg/btn_ativo_menu_rodape.svg\"
                class=\"ms_barra_fundo_imagem wow animate__fadeInUp\"
                data-wow-duration=\"0.2s\"
                data-wow-delay=\"0s\"
                />
                <span
                ms_barra_fundo_icone_texto
                class=\"ms_barra_fundo_icone_texto wow animate__rubberBand\"
                data-wow-duration=\"0.5s\"
                data-wow-delay=\"0.3s\"
                >
                    {$rotulo}
                </span>
                <img
                ms_barra_fundo_icone
                src=\"svg/ico/{$ico}_preto.svg\"
                class=\"ms_barra_fundo_icone wow animate__tada\"
                data-wow-duration=\"0.5s\"
                data-wow-delay=\"0s\"
                opc=\"{$ico}\"
                componente=\"{$componente}\"
                local=\"{$local}\"
                />
        ".(($ico == 'sacola')?"

            <span
            ms_barra_fundo_icone_sacola_up
            class=\"ms_barra_fundo_icone_sacola_up wow animate__rubberBand animate__infinite\"
            data-wow-duration=\"0.5s\"
            data-wow-delay=\"0.3s\"
            ></span>

            <span
            ms_barra_fundo_icone_sacola_down
            class=\"ms_barra_fundo_icone_sacola_down wow animate__rubberBand animate__infinite\"
            data-wow-duration=\"0.5s\"
            data-wow-delay=\"0.3s\"
            ></span>

        ":false)."
        ";


    }


?>
<style>
    .ms_barra_fundo_fixo{
        position:fixed;
        left:0px;
        bottom:0;
        width:100%;
        height:80px;
        z-index: 5;
        background-color:#fff;
    }
    .ms_barra_fundo_fixo_botao{
        position:relative;
        border:solid 0px red;
        height:80px;
        text-align:center;
    }
    .ms_barra_fundo_imagem{
        position:absolute;
        width:200%;
        margin-left:-50%;
        margin-top:-55%;
        display:block;
        z-index: 6;
        display: none;
    }

    .ms_barra_fundo_icone{
        position:absolute;
        width:30%;
        left:50%;
        margin-left:-15%;
        margin-top:20%;
        z-index: 7;
    }

    .ms_barra_fundo_icone_ativo{
        position:absolute;
        width:30%;
        left:50%;
        margin-left:-15%;
        margin-top:-10%;
        z-index: 7;
        display: none;
    }
    .ms_barra_fundo_icone_texto{
        position:absolute;
        left:0;
        bottom:0;
        width:100%;
        padding:5px;
        color:#777777;
        font-size: 12px;
        text-align:center;
        border:solid 0px red;
        z-index: 7;
        display: none;
    }

    .ms_barra_fundo_icone_sacola_up{
        position:absolute;
        left:50%;
        margin-left:-2px;
        top:-10px;
        width:5px;
        height:5px;
        padding:5px;
        color:#fff;
        background-color:#fff;
        font-size: 8px;
        text-align:center;
        border:solid 0px red;
        z-index: 8;
        border-radius:100%;
        display:none;
    }

    .ms_barra_fundo_icone_sacola_down{
        position:absolute;
        left:50%;
        margin-left:-2px;
        top:10px;
        width:5px;
        height:5px;
        padding:5px;
        color:#fff;
        background-color:green;
        font-size: 8px;
        text-align:center;
        border:solid 0px red;
        z-index: 8;
        border-radius:100%;
    }

</style>
<div style="height:120px;"></div>

<div class="ms_barra_fundo_fixo">
    <div class="w3-row">
        <div opc='1' class="w3-col s3 ms_barra_fundo_fixo_botao">
            <?=ms_barra_fundo_fixo('informacao','Informações','componentes/ms_popup.php','src/info/index.php')?>
        </div>
        <div opc='2' class="w3-col s3 ms_barra_fundo_fixo_botao">
            <?=ms_barra_fundo_fixo('busca','Busca','componentes/ms_busca_topo_fixo.php')?>
        </div>
        <div opc='3' class="w3-col s3 ms_barra_fundo_fixo_botao">
            <?=ms_barra_fundo_fixo('sacola','Vendas','componentes/ms_popup_100.php','src/usuarios/carrinho.php')?>
        </div>
        <div opc='4' class="w3-col s3 ms_barra_fundo_fixo_botao">
            <?=ms_barra_fundo_fixo('usuario','Perfil','componentes/ms_popup.php','src/usuarios/index.php')?>
        </div>
    </div>
</div>

</div>

<script>
    $(function(){

        $(".ms_barra_fundo_icone_ativoXX").off('click').on('click',function(){
            pai = $(this).parent("div");

            $(".ms_barra_fundo_imagem").css("display","none");
            $(".ms_barra_fundo_icone_texto").css("display","none");
            $(".ms_barra_fundo_icone").css("display","block");

            $(this).css("display","none");

            pai.children("img[ms_barra_fundo_imagem]").css("display","none");
            pai.children("span[ms_barra_fundo_icone_texto]").css("display","none");
            pai.children("img[ms_barra_fundo_icone]").css("display","block");

        });

        $(".ms_barra_fundo_icone").off('click').on('click',function(){
            pai = $(this).parent("div");
            opc = $(this).attr("opc");

            $(".ms_barra_fundo_imagem").css("display","none");
            $(".ms_barra_fundo_icone_ativo").css("display","none");
            $(".ms_barra_fundo_icone_texto").css("display","none");


            CarrinhoOpc();


            $(".ms_busca_topo_fixo").remove();
            $(".ms_busca_topo_fixo_resultado").remove();

            $(".ms_barra_fundo_icone").css("display","block");

            $(this).css("display","none");

            pai.children("span[ms_barra_fundo_icone_texto]").css("display","block");
            pai.children("img[ms_barra_fundo_imagem]").css("display","block");
            pai.children("img[ms_barra_fundo_icone_ativo]").css("display","block");

            componente = $(this).attr("componente");
            local = $(this).attr("local");
            if(componente){
                Carregando();
                $.ajax({
                    url:componente,
                    type:"POST",
                    data:{
                        local
                    },
                    success:function(dados){
                        //$(".ms_corpo").append("<div barra_busca_topo>"+dados+"</div>");
                        $(".ms_corpo").append(dados);
                    }
                });
            }

        });

        CarrinhoOpc();
    })
</script>