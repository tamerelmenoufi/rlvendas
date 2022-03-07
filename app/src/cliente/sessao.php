<?php
    include("../../../lib/includes.php");
    if($_POST['AppCliente']) $_SESSION['AppCliente'];
    if($_POST['AppVenda']) $_SESSION['AppVenda'];
    if($_POST['AppPedido']) $_SESSION['AppPedido'];


    $dados =   "Cliente: ". $_SESSION['AppCliente']."\n".
               "Venda: ". $_SESSION['AppVenda']."\n".
               "Pedido: ". $_SESSION['AppPedido']."\n";

    file_put_contents(date("YmdHis").".txt", $dados);

?>