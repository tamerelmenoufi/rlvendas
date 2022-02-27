<?php
    include("../../lib/includes.php");
?>
<style>

    .ms_popup_100<?=$md5?>{
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
    .ms_popup_100_close<?=$md5?>{
        position:fixed;
        height:40px;
        left:0px;
        top:0px;
        width:50px;
        border:solid 1px #777;
        color:#777;
        border-radius:15px;
        padding:10px;
        margin:10px;
        padding-left:15px;
        z-index:110;
    }

</style>
    <div
        class="ms_popup_100<?=$md5?> wow animate__fadeInRightBig"
        data-wow-duration="0.5s"
        data-wow-delay="0s"
    >
        <close chave="<?=$md5?>"></close>
        <div class="ms_popup_100_close<?=$md5?>">
            <i class="fas fa-long-arrow-alt-left"></i>
        </div>
    </div>

<script>
    $(function(){

        Carregando('none');

        FecharPopUp<?=$md5?> = () => {

            $(".ms_popup_100<?=$md5?>").remove();
        }

        $(".ms_popup_100_close<?=$md5?>").off('click').on('click',function(){
            FecharPopUp<?=$md5?>();
        });

        <?php
        if($_POST['local']){

            $Dados = json_encode($_POST).';';
            echo "Dados{$md5} = ".(($Dados)?:"'';\n\n");
        ?>
        Carregando();
        $.ajax({
            url:"<?=$_POST['local']?>",
            type:"POST",
            data:{
                Dados:<?="Dados{$md5}"?>,
                <?php
                foreach($_POST as $chave => $valor){
                    echo "{$chave}:'{$valor}',\n";
                }
                ?>
            },
            success:function(dados){
                $(".ms_popup_100<?=$md5?>").append(dados);
                Carregando('none');
            },
            error:function(){
                $.alert("Ocorreu um erro no carregamento da página!");
                Carregando('none');
            }
        });
        <?php
        }
        ?>

    })
</script>