<?php
    include("../../lib/includes.php");
?>
<style>
    .ms_popup_fundo<?=$md5?>{
        position:fixed;
        left:0px;
        bottom:0;
        width:100%;
        height:100%;
        background:#000;
        opacity: 0.4;
        z-index: 100;
    }
    .ms_popup<?=$md5?>{
        position:fixed;
        left:0px;
        bottom:0;
        width:100%;
        height:70%;
        padding-top:40px;
        border-top-left-radius:25%;
        border-top-right-radius:25%;
        background-size:cover;
        background-position:center top;
        background-color:#fff;
        z-index: 100;
        overflow:auto;
    }
    .ms_popup_close<?=$md5?>{
        position:absolute;
        top:-20px;
        left:50%;
        margin-left:-50px;
        text-align:center;
        color:#FFF;
        width:110px;
        border-top:6px solid #FFF;
        padding:0;
    }

</style>

<div class="ms_popup_fundo<?=$md5?>"></div>
<div
    class="ms_popup<?=$md5?> wow animate__fadeInUp"
    data-wow-duration="0.5s"
    data-wow-delay="0s"
>
    <close chave="<?=$md5?>"></close>
    <div class="ms_popup_close<?=$md5?>"></div>
</div>

<script>



    FecharPopUp<?=$md5?> = () => {
        $(".ms_popup_fundo<?=$md5?>, .ms_popup<?=$md5?>").remove();
    }

    $(function(){

        Carregando('none');

        $(".ms_popup_fundo<?=$md5?>, .ms_popup_close<?=$md5?>").off('click').on('click',function(){
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
            //*
            foreach($_POST as $ind => $val){
                if($ind != 'local'){
                    echo  "             {$ind}:'{$val}',\n";
                }
            }
            //*/
                ?>
            },
            success:function(dados){
                $(".ms_popup<?=$md5?>").append(dados);
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


        $(".ms_popup_fundo<?=$md5?>").draggable({

            containment: ".ms_corpo",
            //cursor: "move",
            helper: "clone",
            scroll: false,

            start: function () {
                $(".ms_popup_fundo<?=$md5?>, .ms_popup_close<?=$md5?>").click();
                //console.log('start');
            },

        });


    })
</script>