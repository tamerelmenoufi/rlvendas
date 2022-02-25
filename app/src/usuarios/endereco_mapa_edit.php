<?php
   include("../../../../lib/includes.php");
    $d = new \stdClass();
   if($_POST['opc'] == 'coord'){
        $LatLng = explode("|",$_POST['dados']);
        $d->lat = $LatLng[0];
        $d->lng = $LatLng[1];
   }


?>
<style>

</style>
<div id="ms_usuario_endereco_mapa_local<?=$md5?>"></div>

<script>

    $(function(){

        setTimeout(

            () => {
                map<?=$md5?> = new GMaps({
                    div: '#ms_usuario_endereco_mapa_local<?=$md5?>',
                    zoom: 18,
                    lat: ( ('<?=$d->lat?>')?'<?=$d->lat?>':'-3.098170162749315' ),
                    lng: ( ('<?=$d->lng?>')?'<?=$d->lng?>':'-60.010407004276466' ),

                    click: function(e) {
                        alert('Endereco nao editavel');
                    },
                });
            }

        , 500);


        var Lat = ( ('<?=$d->lat?>')?'<?=$d->lat?>':'-3.098170162749315' );
        var Lng = ( ('<?=$d->lng?>')?'<?=$d->lng?>':'-60.010407004276466' );
        var latlng = new google.maps.LatLng(Lat, Lng);
        //console.log('<?=str_replace("-",false,$_POST['dados'])?>');


        setTimeout(

            () => {

        GMaps.geocode({

            //address: '',
        // postalCode: '69038110',

        //    componentRestrictions: {
        //    country: 'BR',
        //    postalCode: '69038110',
            <?php

//*
                switch($_POST['opc']){
                    case 'cep':{
                        echo "
                                componentRestrictions: {\n
                                    country: 'BR',
                                    postalCode: '".str_replace("-",false,$_POST['dados'])."',\n
                                },
                            ";
                        break;
                    }
                    case 'end':{
                        echo "
                                address:'".str_replace("-",false,$_POST['dados'])."',\n
                            ";
                        break;
                    }
                    case 'coord':{
                        echo "
                                location:latlng,\n
                            ";
                    }
                    default:{
                        echo "location:latlng,\n";
                    }
                }
//*/
            ?>



        // },
            callback: function(results, status) {

                //console.log(status);
                //console.log(results);
                //console.log(results[0].geometry.location.lat());

                    if (status == 'OK') {
                        //console.log( results[0].geometry.location.lat() + ' & ' +  results[0].geometry.location.lng() );

                        Nlat = ( ('<?=$d->lat?>')?'<?=$d->lat?>':results[0].geometry.location.lat() );
                        Nlng = ( ('<?=$d->lng?>')?'<?=$d->lng?>':results[0].geometry.location.lng() );

                        map<?=$md5?>.setCenter( Nlat, Nlng );
                        map<?=$md5?>.setZoom(18);


                        map<?=$md5?>.addMarker({ // Função para adicionar o marcador
                            draggable: true,
                            lat: Nlat,
                            lng: Nlng,
                            dragend: function(event) {
                                var lat = event.latLng.lat();
                                var lng = event.latLng.lng();
                                // alert('dragend' +lat+'-'+ lng + ', <?=$d->codigo?>');
                                //console.log(lat + ' *&* ' + lng);
                                <?php
                                if($_POST['end_cod']){
                                ?>
                                $.ajax({
                                    url:"src/usuarios/enderecos.php",
                                    type:'POST',
                                    data:{
                                        lat,
                                        lng,
                                        end_cod:'<?=$_POST['end_cod']?>',
                                        acao:'coordenadas'
                                    },
                                    success:function(dados){
                                        //console.log(dados);
                                        $("#cli_end_latitude").val(lat);
                                        $("#cli_end_longitude").val(lng);

                                    }
                                });
                                <?php
                                }else{
                                ?>
                                        $("#cli_end_latitude").val(lat);
                                        $("#cli_end_longitude").val(lng);
                                <?php
                                }
                                ?>

                            },
                        });



                        <?php
                        /*
                        if($_POST['opc'] == 'cep'){
                        ?>
                        map.setCenter(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                        map.setZoom(18);
                        map.addMarker({ // Função para adicionar o marcador
                            draggable: true,
                            lat: results[0].geometry.location.lat(),
                            lng: results[0].geometry.location.lng(),
                            dragend: function(event) {
                                var lat = event.latLng.lat();
                                var lng = event.latLng.lng();
                                // alert('dragend' +lat+'-'+ lng + ', <?=$d->codigo?>');
                            },
                        });
                        <?php
                        }else{
                        ?>
                        //var latlng = results[0].geometry.location;
                        map.setCenter(latlng.lat(),latlng.lng()); // Função para centralizar
                        map.setZoom(18); // Função para ajuste de zoom
                        map.addMarker({ // Função para adicionar o marcador
                            draggable: true,
                            lat: latlng.lat(),
                            lng: latlng.lng(),
                            dragend: function(event) {
                                var lat = event.latLng.lat();
                                var lng = event.latLng.lng();
                                // alert('dragend' +lat+'-'+ lng + ', <?=$d->codigo?>');
                            },
                        });
                        <?php
                        }
                        //*/
                        ?>

                    } else {
                        window.alert('Geocode was not successful for the following reason: ' + status);
                    }




            }
        });


    }

    , 1000);

    })
</script>