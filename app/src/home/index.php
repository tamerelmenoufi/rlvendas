<?php
    include("../../../lib/includes.php");
?>
<style>
    .bg_home{
        position:absolute;
        width:100%;
        height:100%;
        background-image:url("svg/fundo_home.svg");
        background-size:cover;
        background-color:#EAF3F0;
        opacity:0.05;
        display: flex;
        overflow:none;
    }

</style>
<div class="bg_home"></div>
<script>
    $(function(){
        Carregando();
        $.ajax({
                url:"src/home/home.php",
                data:{
                    cliente: '<?=$_SESSION['ms_cli_codigo']?>',
                },
                success:function(dados){
                    $(".ms_corpo").html(dados);
                }
            });
    })
</script>
