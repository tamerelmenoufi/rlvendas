<?php
    include("../../../lib/includes.php");
    ProdutosCarrinho();

    if($_POST['obs']){
        mysql_query("update vendas_produtos set vp_observacoes='".utf8_decode($_POST['obs'])."' where codigo = '{$_POST['codigo']}'");
        exit();
    }

    ProdutosCarrinho();
?>
<style>

    .ms_popup_obs<?=$md5?>{
        position:fixed;
        top:0px;
        left:0px;
        right:0px;
        bottom:0px;
        padding-top:65px;
        background:#fff;
        z-index: 100;
        overflow:auto;
    }
    .ms_popup_obs_close<?=$md5?>{
        position:fixed;
        height:40px;
        left:0px;
        top:0px;
        width:50px;
        border:solid 1px #777;
        color:#777;
        border-radius:15px;
        padding:5px;
        margin:5px;
        padding-left:15px;
        z-index:110;
    }

</style>
<div
    class="ms_popup_obs<?=$md5?> w3-padding wow animate__fadeInUpBig"
    data-wow-duration="0.5s"
    data-wow-delay="0s"
>
    <close chave="<?=$md5?>"></close>
    <div class="ms_popup_obs_close<?=$md5?>">
        <i class="fas fa-angle-down fa-2x"></i>
    </div>

    <div style="margin-top:65px;">
        <p style="color:#777">Digite suas observações:</p>
        <?php
            if($Carrinho['produto'][$_GET['codigo']]){
        ?>
        <textarea obs_texto<?=$md5?> class="form-control" style="height:100px; color:#777" placeholder="Clique aqui"><?=$Carrinho['obs'][$_GET['codigo']]?></textarea>
        <?php
            }else{
        ?>
        <center> <p style="color:red; font-size:12px; margin-top:50px;"><b>PARA ADICIONAR ALGUMA OBSERVAÇÃO, O PRODUTO PRECISA SER ADICIONADO AO CARRINHO DE COMPRAS!</b></p> </center>
        <?php
            }
        ?>
    </div>
    <div style="position:absolute; bottom:5px; left:5px; right:5px;">
        <button obs_incluir<?=$md5?> class="btn btn-<?=(($Carrinho['produto'][$_GET['codigo']])?'success':'danger')?> btn-block"><?=(($Carrinho['produto'][$_GET['codigo']])?'Incluir':'Fechar')?></button>
    </div>


</div>

<script>
    $(function(){
        Carregando('none');

        FecharPopUp<?=$md5?> = () => {
            texto = $("textarea[obs_texto<?=$md5?>]").val();
            if(texto){
                $("campo_obs").text(texto);
            }else{
                $("campo_obs").text('Digite qui suas observações');
            }

            $.ajax({
                url:"componentes/ms_popup_obs.php",
                type:"POST",
                data:{
                    obs:texto,
                    codigo:'<?=$Carrinho['codigo'][$_GET['codigo']]?>'
                },
                success:function(dados){
                    console.log(dados);
                }
            });

            $("div[ms_popup_obs]").remove();
        }


        $(".ms_popup_obs_close<?=$md5?>, button[obs_incluir<?=$md5?>]").off('click').on('click',function(){
            FecharPopUp<?=$md5?>();
        });

    })
</script>