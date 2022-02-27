<?php include("../lib/includes.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APP</title>
    <?php include("../lib/header.php"); ?>
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/app.css">

</head>
<body>
    <div class="Carregando">
        <span>
            <i class="fa-solid fa-arrows-rotate"></i>
        </span>
    </div>
    <div class="ms_corpo"></div>

    <?php include("../lib/footer.php"); ?>

    <script src="<?= "js/app.js"; ?>"></script>
    <script src="<?= "js/wow.js"; ?>"></script>


    <script>

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