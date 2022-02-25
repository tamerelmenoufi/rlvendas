<?php

    include("../../../../../lib/includes.php");

?>

<div class="w3-row w3-padding">
    <div class="w3-col s12 w3-padding">
        Informe o CEP do seu endereço.
    </div>
    <div class="w3-col s12 w3-padding">
        <input id="cep<?=$md5?>" type="text" class="form-control w3-center" />
    </div>
    <div class="w3-col s12 w3-padding">
        <bottom id="avancar<?=$md5?>" class="btn btn-primary btn-block">AVANÇAR</bottom>
    </div>
    <div muda_endereco class="w3-col s12 w3-padding w3-center">
        Não sabe o seu CEP?
        <p>Clique aqui e cadastre pelo endereço.</P>
    </div>

</div>

<script>
    $(function(){
        Carregando('none');

        $("#avancar<?=$md5?>").click(function(){
            cep = $("#cep<?=$md5?>").val();

            local="src/usuarios/cadastro/cep_dados.php";
            $.ajax({
                url:"componentes/ms_popup.php",
                type:"POST",
                data:{
                    cep,
                    local
                },
                success:function(dados){
                    PageClose();
                    $(".ms_corpo").append(dados);
                }
            });

        });

        $("div[muda_endereco]").click(function(){

            local="src/usuarios/cadastro/rua.php";
            $.ajax({
                url:"componentes/ms_popup.php",
                type:"POST",
                data:{
                    local
                },
                success:function(dados){
                    PageClose();
                    $(".ms_corpo").append(dados);
                }
            });

        });

    })
</script>