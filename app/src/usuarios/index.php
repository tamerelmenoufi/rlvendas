<?php
    include("../../../../lib/includes.php");
?>
<div tela_perfil></div>
<script>
    $(function(){
        if(ms_cli_codigo > 0){
            local = "home";
        }else{
            local = 'pre_cadastro';
        }

        $.ajax({
            url:"src/usuarios/"+local+".php",
            success:function(dados){
                $("div[tela_perfil]").html(dados);
            }
        });
    })
</script>