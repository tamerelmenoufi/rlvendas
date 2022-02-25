<?php
   include("../../../../lib/includes.php");


    $select = "SELECT c.*, b.brs_bairro FROM clientes_enderecos c INNER JOIN bairros b ON c.cli_end_bairro = b.codigo WHERE c.cli_end_padrao = '1' and c.cli_codigo = '{$_SESSION['ms_cli_codigo']}'";

    $select = "SELECT * FROM clientes_enderecos WHERE cli_end_padrao = '1' and cli_codigo = '{$_SESSION['ms_cli_codigo']}'";
    $result = mysql_query($select);
    $d = mysql_fetch_object($result);

?>
<style>
    #ms_usuario_endereco_mapa_local<?=$md5?>{
        position:absolute;
        width:100%;
        height:100%;
    }
</style>
<div id="ms_usuario_endereco_mapa_local<?=$md5?>"></div>

<script>

    $(function(){



        map<?=$md5?> = new GMaps({
            div: '#ms_usuario_endereco_mapa_local<?=$md5?>',
            zoom: 16,
            lat: -3.098170162749315,
            lng: -60.010407004276466,
        });

    // var rua =<?=$d->cli_end_rua?>;
    // var numero =<?=$d->cli_end_numero?>;
    // var bairro =<?=$d->cli_end_bairro?>;

    end ='<?=$d->cli_end_rua?>, <?=$d->cli_end_numero?>, <?=utf8_encode($d->cli_end_bairro)?>, manaus, AM, BRASIL';

    //console.log(end+"\n\n"+"<?=$select?>");


        <?php
        if($d->cli_end_latitude and $d->cli_end_longitude){
        ?>
        var Lat = ( ('<?=$d->cli_end_latitude?>') ? '<?=$d->cli_end_latitude?>':latlng.lat() );
        var Lng = ( ('<?=$d->cli_end_longitude?>') ? '<?=$d->cli_end_longitude?>':latlng.lng() );
        var latlng = new google.maps.LatLng(Lat, Lng);
        <?php
        }
        ?>

        GMaps.geocode({
        <?php
        if($d->cli_end_latitude and $d->cli_end_longitude){
            echo "location:latlng,";
        }else{
            echo "address: end,";
        }
        ?>

        //address: end,
        // postalCode: '69038110',
        //location:latlng,
        //    componentRestrictions: {
        //    country: 'BR',
        //    postalCode: '69038110',
        // },

      callback: function(results, status) {
        if (status == 'OK') {
            //console.log(results);

        var latlng = results[0].geometry.location;
        var Lat = ( ('<?=$d->cli_end_latitude?>') ? '<?=$d->cli_end_latitude?>':latlng.lat() );
        var Lng = ( ('<?=$d->cli_end_longitude?>') ? '<?=$d->cli_end_longitude?>':latlng.lng() );

        map<?=$md5?>.setCenter(Lat, Lng); // Função para centralizar
        map<?=$md5?>.setZoom(18); // Função para ajuste de zoom
        map<?=$md5?>.addMarker({ // Função para adicionar o marcador
            lat: Lat,
            lng: Lng,
        });
        }
        else {
              alert('Endereço não encontrado');
        }
      }
});



    })
</script>