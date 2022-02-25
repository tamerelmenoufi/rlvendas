<?php
    include("../lib/includes/includes.php");
?>
<style>
    .ms_busca_topo_fixo{
        position:fixed;
        left:0px;
        top:0;
        width:100%;
        height:60px;
        background:#fff;
        z-index: 15;
    }

    .ms_busca_topo_fixo span{
        position: absolute;
        top:10px;
        <?=(($_POST['ItemLista'])?'left:65px;':'left:10px;')?>
        <?=(($_POST['ItemLista'])?'right:10px;':'right:110px;')?>
    }

    .ms_busca_topo_fixo span input{
        position: relative;
        <?=(($_POST['ItemLista'])?'width:calc(100% - 65px;)':'width:100%;')?>
        padding:5px;
        padding-left:35px;
        height:40px;
        background-color:#F1F4F3;
        background-image:url(svg/ico/search-solid.svg);
        background-position:left 10px center;
        background-size:20px 20px;
        background-repeat:no-repeat;
        border:0;
        border-radius:10px;
        color:#777777;
    }

    .ms_busca_topo_fixo button{
        position:absolute;
        right:5px;
        top:10px;
        width:100px;
        height:40px;
        border-radius:10px;
        color:#4CBB5E;
    }

    .ms_busca_topo_fixo_resultado{
        position:fixed;
        left:0px;
        top:60px;
        bottom:0;
        width:100%;
        background:#fff;
        display:none;
        overflow:auto;
    }


</style>
<div class="ms_busca_topo_fixo">
    <span><input valor_busca type="text" value="<?=$_POST['ItemLista']?>" /></span>
    <?php
    if(!$_POST['ItemLista']){
    ?>
    <button fecha_busca
            type="button"
            class="btn btn-light"
    >
        Cancelar
    </button>
    <?php
    }
    ?>
</div>
<div class="ms_busca_topo_fixo_resultado"></div>

<script>
    $(function(){
        Carregando('none');

        <?php
            if(strlen(trim($_POST['ItemLista']))){
        ?>
            $(".ms_busca_topo_fixo_resultado").css("display","block");
            $.ajax({
                url:"componentes/ms_card_produtos_50.php",
                type:"POST",
                data:{
                    busca:'<?=trim($_POST['ItemLista'])?>',
                },
                success:function(dados){
                    $(".ms_busca_topo_fixo_resultado").html(dados);
                }
            });
        <?php
            }
        ?>

        $("button[fecha_busca]").off('click').on('click', function(){
            $(".ms_barra_fundo_imagem").css("display","none");
            $(".ms_barra_fundo_icone_texto").css("display","none");
            $(".ms_barra_fundo_icone_ativo").css("display","none");
            $(".ms_barra_fundo_icone").css("display","block");
            $(".ms_busca_topo_fixo").remove();
            $(".ms_busca_topo_fixo_resultado").remove();
        });

        $("input[valor_busca]").keyup(function(){
            busca = $(this).val();
            if(busca && busca.length > 2){
                //Carregando();
                $(".ms_busca_topo_fixo_resultado").css("display","block");
                $.ajax({
                    url:"componentes/ms_card_produtos_50.php",
                    type:"POST",
                    data:{
                        busca
                    },
                    success:function(dados){
                        $(".ms_busca_topo_fixo_resultado").html(dados);
                    }
                });
            }else{
                $(".ms_busca_topo_fixo_resultado").css("display","none");
                $(".ms_busca_topo_fixo_resultado").hrml('');
            }
        });




    })
</script>