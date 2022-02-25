<?php
    include("../../lib/includes/includes.php");

    if($_GET['cliente']) $_SESSION['ms_cli_codigo'] = $_GET['cliente'];
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
    .botao_rodape{
        position: absolute;
        top:50%;
        margin-top:-90px;
        text-align:center;
        width:100%;
    }
    .botao_rodape p{
        color:#333;
    }

</style>
<div class="bg_home"></div>
<div class="botao_rodape" >
    <img id="AnimaIconeHome" src="img/logo_db.png" org="logoms" style="width:150px;" />
    <br><br>
    <!--<p style="font-style: italic;font-size:15px">Delivery</p>--->
</div>

<script>
    $(function(){



        document.getElementById("AnimaIconeHome").animate([
            // keyframes
            { transform: 'scale(0)' },
            { transform: 'scale(1.7)' }
            ], {
            // timing options
            duration: 4000,
            iterations: Infinity
        });


        setTimeout(function(){
            $.ajax({
                url:"src/home/home.php",
                data:{
                    cliente: '<?=$_SESSION['ms_cli_codigo']?>',
                },
                success:function(dados){
                    $(".ms_corpo").html(dados);
                }
            });
        }, 3000);
    })
</script>
