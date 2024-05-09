<?php
include("../lib/includes.php");
// header("location:https://yobom.com.br/rlvendas/panel/");
// exit();

if (!isset($_SESSION['usuario'])) {
    header("Location: ./login");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PAINEL DE CONTROLE</title>
    <?php include("../lib/header.php"); ?>
    <style>
        .TelaVendas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            bottom: 0;
            background: #fff;
            display: none;
            z-index: 999;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">

    <!-- Sidebar -->
    <?php include "home/sidebar.php"; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <?php include "home/header.php"; ?>

        <!-- Main Content -->
        <div id="content" style="position: relative">
            <div class="body">
                <div class="loading">
                    <div class="loader"></div>
                </div>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div id="palco">
                        <?php include "home/content.php"; ?>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <?php include "home/footer.php"; ?>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<div class="TelaVendas"></div>
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


        /*$.ajax({
            url: "home/index.php",
            success: function (dados) {
                $(".body").html(dados);
            },
            error: function () {
                $.alert('Ocorreu um erro!');
            }
        });*/


        $(document).on('click', ".fecharTelaVendas", function () {
            $(".TelaVendas").css("display", "none");
            $(".TelaVendas").html('');
        });

    });
</script>

<script>
    $(function () {
        $("#sidebarToggle").click(function () {
            opc = $(this).attr('opc');
            if (opc == '0') {
                $("#page-top").addClass('sidebar-toggled');
                $(".menus").addClass('toggled');
                $(this).attr("opc", "1");
            } else {
                $("#page-top").removeClass('sidebar-toggled');
                $(".menus").removeClass('toggled');
                $(this).attr("opc", "0");
            }
        });

        $("a[AbrirVendas]").click(function () {
            $('.loading').fadeIn(200);

            $.ajax({
                url: "vendas/home.php",
                success: function (data) {
                    $(".TelaVendas").html(data);
                }
            })
                .done(function () {
                    $('.loading').fadeOut(200);
                    $(".TelaVendas").css("display", "block");
                })
                .fail(function (error) {
                    alert('Error');
                    $('.loading').fadeOut(200);
                });
        });
    });

    $(document).on('click', '[url]', function (e) {
        e.preventDefault();

        var url = $(this).attr('url');

        $('.loading').fadeIn(200);

        $.ajax({
            url,
            success: function (data) {
                $('#palco').html(data);
            }
        })
            .done(function () {
                $('.loading').fadeOut(200);
            })
            .fail(function (error) {
                alert('Error');
                $('.loading').fadeOut(200);
            })
    });
</script>
</body>
</html>