<?php include("../lib/includes.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TERMINAL</title>
    <?php include("../lib/header.php"); ?>
</head>
<style>
    body{
        background-color: #f9f9f9;
    }
</style>
<body id="page-top">

<div id="body"></div>

<?php include("../lib/footer.php"); ?>

<script>
    $(function () {

        // JanelaPopup({
        //   title:'Tamer Mohamed Elmenoufi',
        //   //bottom:"Rodapé do aplicação"
        // });

        $.ajax({
            url: "home/index.php",
            success: function (dados) {
                $("#body").html(dados);
            },
            error: function () {
                $.alert('Ocorreu um erro!');
            }
        });

        //Configurações globais

        //Jconfirm
        jconfirm.defaults = {
            theme: "modern",
            type: "blue",
            typeAnimated: true,
            smoothContent: true,
            draggable: false,
            animation: 'bottom',
            closeAnimation: 'top',
            animateFromElement: false,
            animationBounce: 1.5
        }
    });
</script>
</body>
</html>