<?php
    include("../../../lib/includes.php");
    if($_POST['c']) $_SESSION['AppCliente'];
    if($_POST['v']) $_SESSION['AppVenda'];
    if($_POST['p']) $_SESSION['AppPedido'];

    $dados =   "Cliente: ". $_SESSION['AppCliente']."\n".
               "Venda: ". $_SESSION['AppVenda']."\n".
               "Pedido: ". $_SESSION['AppPedido']."\n";

    file_put_contents(date("YmdHis").".txt", $dados);

?>