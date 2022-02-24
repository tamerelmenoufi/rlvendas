<?php include("../lib/includes.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAINEL DE CONTROLE</title>
    <?php include("../lib/header.php"); ?>
</head>
<body id="page-top">

    <div class="body"></div>

    <?php include("../lib/footer.php"); ?>

    <script>
        $(function(){
            $.ajax({
                url:"home/index.php",
                success:function(dados){
                    $(".body").html(dados);
                },
                error:function(){
                    $.alert('Ocorreu um erro!');
                }
            });
        })
    </script>
</body>
</html>