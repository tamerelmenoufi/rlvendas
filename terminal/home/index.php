<?php
include "../../lib/includes.php";

if ($_GET['mesa']) {
    $_SESSION['ConfMesa'] = $_GET['mesa'];
}

if ($_GET['sair']) {

    $query = "select * from vendas_produtos where venda = '{$_SESSION['ConfVenda']}' and deletado != '1' and situacao = 'n'";
    $result = mysqli_query($con, $query);
    $n = mysqli_num_rows($result);
    if($n > 0 and !$_GET['confirm']){
        echo json_encode([
            "status" => "erro",
        ]);
        exit();
    }else if($_GET['confirm']){
        $_SESSION = [];

    }else{
        echo json_encode([
            "status" => "sucesso",
        ]);
        $_SESSION = [];
        exit();
    }

}

?>

<style>
    .body {
        position: fixed;
        top: 40px;
        bottom: 15px;
        left: 0;
        width: 100%;
    }
</style>

<div class="body"></div>

<script>
    $(function () {
        $.ajax({
            url: "home/header.php",
            success: function (dados) {
                $(".body").append(dados);
            }
        });

        $.ajax({
            url: "home/footer.php",
            success: function (dados) {
                $(".body").append(dados);
            }
        });

        // $.ajax({
        //     url:"home/comanda.php",
        //     success:function(dados){
        //         $(".body").append(dados);
        //     }
        // });

        $.ajax({
            url: "home/cardapio.php",
            success: function (dados) {
                $(".body").append(dados);
            }
        });

        <?php
        if($_SESSION['ConfMesa'] and !$_SESSION['ConfCliente']){
        ?>

        JanelaDefineCliente = $.dialog({
            content: "url:home/definir_cliente.php",
            title: false,
            columnClass: "col-md-8 col-md-offset-2",
            closeIcon: false,
        });
        <?php
        }
        ?>
    });
</script>