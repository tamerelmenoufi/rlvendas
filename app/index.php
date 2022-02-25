<?php
    //include("./lib/includes/includes.php");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <title>MsDelivery</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="http://www.msdelivery.com.br/sis//assets/favicon.png">
    <!-- W3c -->
    <link rel="stylesheet" href="http://www.msdelivery.com.br/sis/lib/v1/w3c/w3.css">

    <!-- Jquery-->
    <!--<script src="http://www.msdelivery.com.br/sis/lib/v1/jquery/jquery.min.js"></script>-->
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>



    <!-- API GOOGLE MAP -->
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="http://www.msdelivery.com.br/sis/lib/v1/gmaps/gmaps.js?key=AIzaSyBII-604JaMdfYQjwCUofXVTe7FskRcvrs"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBII-604JaMdfYQjwCUofXVTe7FskRcvrs&callback=initMap"></script>



    <!-- Boostrap 3-->
    <link rel="stylesheet" href="http://www.msdelivery.com.br/sis/lib/v1/bootstrap4/css/bootstrap.min.css">
    <script src="http://www.msdelivery.com.br/sis/lib/v1/bootstrap4/js/bootstrap.min.js"></script>

    <!-- Bootstrap Datepicker -->
    <link rel="stylesheet" href="http://www.msdelivery.com.br/sis/lib/v1/boostrap-datepicker/css/bootstrap-datepicker.min.css">
    <script src="http://www.msdelivery.com.br/sis/lib/v1/boostrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="http://www.msdelivery.com.br/sis/lib/v1/font-awesome/css/all.min.css">
    <script src="http://www.msdelivery.com.br/sis/lib/v1/font-awesome/js/all.min.js"></script>

    <!-- Jquery Confirm -->
    <link rel="stylesheet" href="http://www.msdelivery.com.br/sis/lib/v1/jquery-confirm/css/jquery-confirm.css">
    <script src="http://www.msdelivery.com.br/sis/lib/v1/jquery-confirm/js/jquery-confirm.js"></script>


    <!-- Jquery Fileinput -->
    <script src="http://www.msdelivery.com.br/sis/lib/v1/jquery-fileinput/jquery.fileinput.min.js"></script>

    <!-- Jquery Maskinput -->
    <script src="http://www.msdelivery.com.br/sis/lib/v1/jquery-maskedinput/jquery.maskedinput.min.js"></script>

    <!-- Jquery MaskMoney -->
    <script src="http://www.msdelivery.com.br/sis/lib/v1/jquery-maskmoney/jquery.maskMoney.min.js"></script>

    <!-- Jquery Validation -->
    <script src="http://www.msdelivery.com.br/sis/lib/v1/jquery-validation/js/jquery.validate.min.js"></script>

    <!-- Ckeditor5 -->
    <script src="http://www.msdelivery.com.br/sis/lib/v1/ckeditor/ckeditor.js"></script>

    <!-- Estilo global -->
    <link rel="stylesheet" href="http://www.msdelivery.com.br/sis/lib/v1/global/style.css">

    <!-- Font Feather -->
    <script src="http://www.msdelivery.com.br/sis/lib/v1/feather/feather.min.js"></script>


    <!-- ANIMATE CSS AND WOW JS -->
    <link rel="stylesheet" href="css/animate.css" />
    <script src="js/wow.js"></script>

    <!-- Biblioteca UIjquery e touch -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
    <script type="text/javascript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script type="text/javascript" src="http://www.pureexample.com/js/lib/jquery.ui.touch-punch.min.js"></script>


    <!-- APP Style -->
    <link rel="stylesheet" href="css/ms.css?<?=$md5?>">
    <script src="js/ms.js?<?=$md5?>"></script>


</head>
<body>
    <div class="Carregando">
        <span>
            <i class="fas fa-spinner fa-5x fa-w-16 fa-spin fa-lg"></i>
        </span>
    </div>
    <div class="ms_corpo"></div>
    <script>
        Anima();

        ms_cli_codigo = window.localStorage.getItem('ms_cli_codigo');

        $(function(){
            $.ajax({
                url:"src/home/index.php",
                data:{
                    cliente: ms_cli_codigo,
                },
                success:function(dados){
                    $(".ms_corpo").html(dados);
                }
            });
        })
    </script>
<form></form>
</body>
</html>