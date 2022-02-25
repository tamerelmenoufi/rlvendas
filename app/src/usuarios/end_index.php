<?php
    include("../../../../lib/includes.php");
?>
<div tela_end></div>
<script>
    $(function(){
        if(ms_cli_codigo > 0){
            local = "src/usuarios/lista_end.php";
        }else{
             local = "src/usuarios/index.php";
        }

        $.ajax({
            url:local,
            success:function(dados){
                $("div[tela_end]").html(dados);
            }
        });

    })
</script>