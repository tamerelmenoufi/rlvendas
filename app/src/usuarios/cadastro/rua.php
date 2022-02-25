<?php

    include("../../../../../lib/includes.php");

?>

<div class="w3-row w3-padding">
    <div class="w3-col s12 w3-padding">
        Informe o seu endereço.
    </div>
    <div class="w3-col s12 w3-padding">
        <input id="rua<?=$md5?>" type="text" class="form-control w3-center" />
    </div>
    <div ListaRuas class="w3-col s12 w3-padding">

    </div>

</div>

<script>
    $(function(){
        Carregando('none');

        $("#rua<?=$md5?>").blur(function(){
            endereco = $("#rua<?=$md5?>").val();


            GMaps.geocode({

                address: endereco, //+ ', manaus, Amazonas, Brasil',

                callback: function(results, status) {

                    console.log(status);
                    console.log(results);

                    for(i=0; i< results.length; i++){
                        dados = results[i].address_components;
                        for(j=0;j<dados.length;j++){
                            console.log(dados[j].long_name);
                        }
                        console.log('-------------------------------');
                    }

                        if (status == 'OK') {


                        } else {
                            window.alert('Geocode was not successful for the following reason: ' + status);
                        }




                }
            });



        });


    })
</script>