<?php
    include("../../../lib/includes.php");
    if($_POST['AppCliente']) $_SESSION['AppCliente'] = $_POST['AppCliente'];
    if($_POST['AppVendav']) $_SESSION['AppVenda'] = $_POST['AppVendav'];
    if($_POST['AppPedidop']) $_SESSION['AppPedido'] = $_POST['AppPedidop'];

    $dados =   "Cliente: ". $_SESSION['AppCliente']."\n".
               "Venda: ". $_SESSION['AppVenda']."\n".
               "Pedido: ". $_SESSION['AppPedido']."\n";

    file_put_contents(date("YmdHis").".txt", $dados."\n\n".print_r($_POST, true));

?>