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
</head>
<body id="page-top">

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
    });

    $(function () {
        $.ajax({
            url: "home/index.php",
            success: function (dados) {
                $(".body").html(dados);
            },
            error: function () {
                $.alert('Ocorreu um erro!');
            }
        });


    })
</script>
</body>
</html>