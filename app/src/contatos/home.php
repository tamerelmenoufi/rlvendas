<?php
    include("../../lib/includes/includes.php");
?>
<style>
    .ms_contatos_home_titulo_topo{
        position:fixed;
        left:0;
        top:0;
        width:100%;
        height:65px;
        background:#fff;
        text-align:center;
        color:#777;
        font-size:18px;
        font-weight:bold;
        z-index:10;
        padding:15px;
    }
    .ms_contatos_home_icones{
        width:auto;
        height:auto;
        padding:30px;
        border-radius:20px;
        background-color:#EBF4F1;
        color:#2AAF7F;
        font-weight:bold;
    }
    .ms_contatos_home_cartao{
        width:auto;
        height:auto;
        margin:20px;
        padding:10;
        border-radius:20px;
        color:#2AAF7F;
    }
    .ms_contatos_home_cartao h3{
        font-size:18px;
        font-weight:bold;
    }
    .ms_contatos_home_cartao p{
        font-size:12px;
    }

</style>

<div class="ms_contatos_home_titulo_topo">Fale Conosco</div>

    <div class="w3-row" style="margin-top:25px;">
        <div class="w3-col s4" style="text-align:center">
            <span
                acao<?=$md5?>
                class="ms_contatos_home_icones"
                caminho="componentes/ms_popup_100.php"
                local="src/contatos/chat.php"
            >
                <i class="far fa-comment-dots fa-2x"></i>
            </span>
        </div>
        <div class="w3-col s4" style="text-align:center">
            <span
                acao<?=$md5?>
                class="ms_contatos_home_icones"
                caminho="componentes/ms_popup.php"
                local="src/contatos/chat.php"
            >
                <i class="fab fa-whatsapp fa-2x"></i>
            </span>
        </div>
        <div class="w3-col s4" style="text-align:center">
            <span
                acao<?=$md5?>
                class="ms_contatos_home_icones"
                caminho="componentes/ms_popup.php"
                local="src/contatos/chat.php"
            >
                <i class="fas fa-at fa-2x"></i>
            <span>
        </div>
    </div>

    <div class="w3-row" style="margin-top:20px;">
        <div class="w3-col s12">
            <div class="ms_contatos_home_cartao" style="background-color:#EBF4F1">
                <div class="w3-row">
                    <div class="w3-col s3" style="text-align:center; padding-top:40px;">
                        <span style="padding:30px;">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </span>
                    </div>
                    <div class="w3-col s9">
                        <span style="padding:30px;">
                            <h3>Meios de Contato</h3>
                            <p>
                                <?php print_r($_SESSION); ?><br>
                                Selecione um dos ícones acima para ativar o contato concosco.
                            </p>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(function(){
        Carregando('none');
        $("span[acao<?=$md5?>]").off('click').on('click', function(){
            local = $(this).attr("local");
            caminho = $(this).attr("caminho");
            Carregando();
            $.ajax({
                url:caminho,
                type:"POST",
                data:{
                    local
                },
                success:function(dados){
                    $(".ms_corpo").append("<div barra_busca_topo>"+dados+"</div>");
                }
            });
        })
    })
</script>