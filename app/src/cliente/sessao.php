<?php
    include("../../../lib/includes.php");
    if($_POST['AppCliente']) $_SESSION['AppCliente'] = $_POST['AppCliente'];
    if($_POST['AppVenda']) $_SESSION['AppVenda'] = $_POST['AppVenda'];
    if($_POST['AppPedido']) $_SESSION['AppPedido'] = $_POST['AppPedido'];


    // $dados =   "Cliente: ". $_SESSION['AppCliente']."\n".
    //            "Venda: ". $_SESSION['AppVenda']."\n".
    //            "Pedido: ". $_SESSION['AppPedido']."\n";

    // file_put_contents(date("YmdHis").".txt", $dados."\n\n".print_r($_POST, true));

?>