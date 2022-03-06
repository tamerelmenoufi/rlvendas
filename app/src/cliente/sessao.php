<?php
    include("../../../lib/includes.php");
    if($_POST['AppCliente']) $_SESSION['AppCliente'];
    if($_POST['AppVenda']) $_SESSION['AppVenda'];
    if($_POST['AppPedido']) $_SESSION['AppPedido'];

    file_put_contents(date("YmdHis").".txt", "Cliente:{$_SESSION['AppCliente']}\nVenda:{$_SESSION['AppVenda']}\nPedido:{$_SESSION['AppPedido']}\n");
?>