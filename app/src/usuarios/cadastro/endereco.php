<?php

    include("../../../../../lib/includes.php");

?>
<style>
    .resultado_enderecos{
    /*margin-top: 50px;
    align-items: center;
    justify-content: center*/
        margin-left: 32px;
    }
    .ItemEndereco{
        position:relative;
        width: 100%;
        height: auto;
        border: solid 0px#ccc;
        border-radius: 5px;
        margin-bottom: 15px;
        padding: 5px;
        color: #333;
        font-size: 13px;
        /*
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        display: flex;
        align-content: center;
        align-items: center;
        justify-content: flex-start;
        */
    }

    .ItemEndereco_i{
        position:absolute;
        left:-10px;
        top:5px;
    }
    .ms_usuario_endereco_form_campo{
    position:relative;
    width:100%;
    padding:0px 10px 10px 10px;
    height:50px;
}
    .ms_usuario_endereco_form_campo input{
       position:relative;
        width:100%;
        height:50px;
        background-color:#F1F3F2;
        background-position:left 15px center;
        background-size:20px;
        background-repeat:no-repeat;
        color:#777;
        border-radius:10px;
        font-size:18px;
        border:0;
    }
    .ms_usuario_endereco_titulo_form_rotulo{
        position:relative;
        margin-bottom:0px;
        margin-left:15px;
        color:#ccc;
        font-size:12px;
    }
    .icon_{
        position:absolute;
        left:20px;
        top:15px;
        color:#777;
        font-size:20px;
        z-index:1;
    }



</style>
<div class="w3-row w3-padding">
    <div class="w3-col s12 w3-padding">
        Digite seu Endereço ou seu CEP.
    </div>
  <!--   <div class="w3-col s12 w3-padding">
        <input id="endereco<?=$md5?>" type="text" class="form-control w3-center" />
    </div> -->

<div>
    <div class="ms_usuario_endereco_titulo_form_rotulo">
</div>
    <div class="ms_usuario_endereco_form_campo">
    <input id="endereco<?=$md5?>" ativar="<?=$_POST['ativar']?>" type="text" class="form-control w3-center"/>
    </div>
</div>



<div style="display: flex;
    flex-direction: row;
    justify-content: center;">

<div ListaEnderecos style="

    flex-direction: row;
    display: flex;
    margin-top: 69px;
    ">


        <div  class="resultado_enderecos">
            
        </div>

    </div>

</div>

<div style="display: flex;
    flex-direction: row;
    justify-content: center;">

<div style="
    display: flex;
    margin-top: 50px;
    ">


        <div >
            <button end_n_sei_cep  class="btn btn-success btn-block" style=" border-radius:10px;">Não sei meu cep</button>
        </div>

    </div>

</div>

<script>
    $(function(){
        Carregando('none');

  $("button[end_n_sei_cep]").off('click').click(function(){
            // alert("ok");
            local="src/usuarios/enderecos.php";
            $.ajax({
                url:"componentes/ms_popup_100.php",
                type:"POST",
                data:{
                    local
                },
                success:function(dados){
                $(".ms_corpo").append(dados);

                }
            });

        });



        $("#endereco<?=$md5?>").click(function(){
            $(this).select();
        });

        $("#endereco<?=$md5?>").blur(function(){
            endereco = $(this).val();
            ativar = $(this).attr('ativar');
            if(endereco.trim()){
                Carregando();
                GMaps.geocode({

                    address: endereco, //+ ', manaus, Amazonas, Brasil',

                    callback: function(results, status) {



                            if (status == 'OK') {

                                console.log(status);
                                console.log(results);

                                $(".resultado_enderecos").html('');

                                ListaDados = [];

                                for(i=0; i< results.length; i++){
                                    dados = results[i].address_components;
                                    $(".resultado_enderecos").append('<div opc="'+i+'" class="ItemEndereco"><i class="fas fa-map-marker-alt ItemEndereco_i"></i>'+results[i].formatted_address+'</div>');
                                    ListaDados[i] = [];
                                    ListaDados[i].push('formatted_address|'+results[i].formatted_address);
                                    for(j=0;j<dados.length;j++){

                                        console.log(dados[j].types[0]);
                                        ListaDados[i].push(dados[j].types[0]+'|'+dados[j].long_name);
                                        console.log(dados[j].long_name);
                                    }
                                    ListaDados[i].push('lat|'+results[i].geometry.location.lat());
                                    ListaDados[i].push('lng|'+results[i].geometry.location.lng());

                                    console.log('-------------------------------');
                                }

                                console.log(ListaDados);
                                $(".ItemEndereco").click(function(){
                                    opcao = $(this).attr("opc");

                                    local="src/usuarios/enderecos.php";
                                    $.ajax({
                                        url:"componentes/ms_popup_100.php",
                                        type:"POST",
                                        data:{
                                            ListaDados,
                                            opcao,
                                            ativar,
                                            local
                                        },
                                        success:function(dados){
                                            //$("div[tela_lista]").html(dados);
                                            // console.log(dados);
                                                $(".ms_corpo").append(dados);

                                        }
                                    });

                                });






                            } else {
                                //window.alert('Geocode was not successful for the following reason: ' + status);
                                $(".resultado_enderecos").html('Que pena, não encontrei seu endereço!');

                            }
                            Carregando('none');

                    }
                });
            }else{
                $(this).val('');
            }



        });


    })
</script>