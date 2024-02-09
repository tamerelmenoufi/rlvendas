<?php
    include("../lib/includes.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#990002"/>
    <title>Loja</title>
    <?php include("../lib/header.php"); ?>
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/app.css">
    <style>
        .popupArea{
            position:absolute;
            left:0;
            bottom:0;
            right:0;
            top:0;
            background-color:rgb(0,0,0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            display:none;
            z-index: 9988;
        }
        .popupFecha{
            position:absolute;
            right:30px;
            top:20px;
            font-size:25px;
            color:#000;
            cursor:pointer;
            z-index:2
        }        
        .popupPalco{
            position:absolute;
            padding:10px;
            padding-top:40px;
            right:8px;
            left:8px;
            top:10px;
            bottom:10px;
            background:#fff;
            border-radius:10px;
            overflow:auto;
            z-index:1
        }
    </style>
</head>
<body>
<div class="Carregando">
    <span>
        <i class="fa-solid fa-spinner"></i>
    </span>
</div>
<div class="popupArea">
    <i class="fa-solid fa-xmark popupFecha"></i>
    <div class="popupPalco"></div>
</div>
<div class="ms_corpo"></div>

<?php include("../lib/footer.php"); ?>

<script src="<?= "js/app.js?" . date("YmdHis"); ?>"></script>
<script src="<?= "js/wow.js"; ?>"></script>

<script>
    $(function () {
        setInterval(() => {
            $.ajax({
                url: "src/index.php",
                success: function (dados) {
                    $(".ms_corpo").html(dados);
                }
            });            
        }, 5000);

        $(".popupFecha").click(function(){
            $(".popupPalco").html('');
            $(".popupArea").css("display","none");
        })

    })

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

</script>
</body>
</html>