<?php
include("../lib/includes.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: ./login");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAINEL DE CONTROLE</title>
    <?php include("../lib/header.php"); ?>
    <style>
        .TelaVendas{
            position: fixed;
            top:0;
            left:0;
            width:100%;
            bottom:0;
            background:#fff;
            display:none;
            z-index:999;
        }
    </style>
</head>
<body id="page-top">
<div class = "TelaVendas"></div>
<div class="body"></div>

<?php include("../lib/footer.php"); ?>

<script>
    $(document).ready(function () {
        //Datatables
        $.extend(true, $.fn.dataTable.defaults, {
            "language": {
                "url": "<?= $caminho_vendor; ?>/datatables/pt_br.json",
                responsive: true
            },
            "order": [],
            "columnDefs": [{
                targets: 'no-sort',
                orderable: false,
            }],
            stateSave: true,
        });

        //Jconfirm
        jconfirm.defaults = {
            typeAnimated: true,
            type: "blue",
            smoothContent: true,
        }


        $.ajax({
            url: "home/index.php",
            success: function (dados) {
                $(".body").html(dados);
            },
            error: function () {
                $.alert('Ocorreu um erro!');
            }
        });


        $(document).on('click', ".fecharTelaVendas", function(){
            $(".TelaVendas").css("display","none");
            $("body::-webkit-scrollbar").css("display",'block');
        });

    });




</script>
</body>
</html>