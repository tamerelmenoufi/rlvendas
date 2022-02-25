<?php

    include("../../../../../lib/includes.php");

?>

<div class="w3-row w3-padding">
    <div class="w3-col s12 w3-padding">
        Rua: <span rua<?=$md5?>></span>
    </div>
    <div class="w3-col s12 w3-padding">
        Bairro: <span bairro<?=$md5?>></span>
    </div>
    <div class="w3-col s12 w3-padding">
        CEP: <span cep<?=$md5?>></span>
    </div>
    <div class="w3-col s12 w3-padding">
        Cidade: <span cidade<?=$md5?>></span>
    </div>
    <div class="w3-col s12 w3-padding">
        Estado: <span estado<?=$md5?>></span>
    </div>
    <div class="w3-col s12 w3-padding">
        País: <span pais<?=$md5?>></span>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');


        GMaps.geocode({

        componentRestrictions: {
            country: 'BR',
            postalCode: '<?=$_POST['cep']?>',
        },
        callback: function(results, status) {

            console.log(status);
            console.log(results);
            console.log(results[0].geometry.location.lat());

            cep = results[0].address_components[0].long_name;
            rua = results[0].address_components[1].long_name;
            bairro = results[0].address_components[2].long_name;
            cidade = results[0].address_components[3].long_name;
            estado = results[0].address_components[4].long_name;
            pais = results[0].address_components[5].long_name;

            $("span[cep<?=$md5?>]").html(cep);
            $("span[rua<?=$md5?>]").html(rua);
            $("span[bairro<?=$md5?>]").html(bairro);
            $("span[cidade<?=$md5?>]").html(cidade);
            $("span[estado<?=$md5?>]").html(estado);
            $("span[pais<?=$md5?>]").html(pais);

            console.log('Endereco: '+cep);

                if (status == 'OK') {
                    console.log( results[0].geometry.location.lat() + ' & ' +  results[0].geometry.location.lng() );


                } else {
                    window.alert('Geocode was not successful for the following reason: ' + status);
                }

        }
        });


    })
</script>