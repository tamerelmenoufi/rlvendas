<?php
    include("../../../lib/includes.php");
    if($_POST['AppCliente']) $_SESSION['AppCliente'] = $_POST['AppCliente'];
    if($_POST['AppVenda']) $_SESSION['AppVenda'] = $_POST['AppVenda'];
    if($_POST['AppPedido']) $_SESSION['AppPedido'] = $_POST['AppPedido'];
    if($_POST['AppGarcom']) $_SESSION['AppGarcom'] = $_POST['AppGarcom'];


    if($_SESSION['AppGarcom']){

        $query = "select * from atendentes where codigo = '{$_SESSION['AppGarcom']}' and (restart = '1' or situacao = '0' or deletado = '1')";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result)){
            $_SESSION = [];
            echo "<script>window.localStorage.clear(); window.location.href='./';</script>";
            exit();
        }

    }


    // $dados =   "Cliente: ". $_SESSION['AppCliente']."\n".
    //            "Venda: ". $_SESSION['AppVenda']."\n".
    //            "Pedido: ". $_SESSION['AppPedido']."\n";

    // file_put_contents(date("YmdHis").".txt", $dados."\n\n".print_r($_POST, true));

?>